<?php

namespace App\Admin\Controllers;

use App\TaskHistory;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('会员管理')
            ->description('')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('会员详情')
            ->description('')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('会员编辑')
            ->description('')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('添加会员')
            ->description('')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);

        $grid->id('ID');
        $grid->invite('已邀请人数')->sortable();
        $grid->column('dyyq', '当月邀请人数')->display(function (){
            return User::where('pid', $this->id)->where('created_at', '>=', Carbon::today()->firstOfMonth())
                ->where('created_at', '<=', Carbon::today()->lastOfMonth())->count();
        });
        $grid->column('device', '设备')->display(function (){
            $device = count(Redis::keys($this->id . ':mac:*'));
            if($device){
                return "<span class='badge bg-green'>在线({$device})</span>";
            }
            return "<span class='badge bg-gray'>离线</span>";
        });
        $grid->username('账号');
        $grid->real_name('姓名');
        $grid->amount('余额');
        $grid->column('amounts','当日收益')->display(function (){
            $date_string = ':' . date('Y-m-d', time());
            $income = round(Redis::get($this->id . $date_string . ':income')/10000,2);
            return $income;
        });
        $grid->one_day_max_send_count('当日最大发送数');
        $grid->created_at('添加时间');

        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->like('username', '账号');
            $filter->where(function ($query) {
                $user = User::where('username', $this->input)->first();
                if(!$user)
                    return false;
                $query->where('pid', $user->id);

            }, '邀请人账号');
            $filter->scope('status', '未冻结')->where('status', 0);
        });
        $grid->actions(function ($actions){
            $actions->disableDelete();
            $actions->append('<a href="'.url('/admin/user/task-history/'.$actions->getKey()).'"><i class="fa fa-history"></i></a>');
        });
        $grid->disableExport();
        $grid->disableRowSelector();

        $grid->footer(function ($query) {
            $ids = $query->pluck('id')->toArray();
            $income = 0;
            foreach ($ids as $id){
                $date_string = ':' . date('Y-m-d', time());
                $income += round(Redis::get($id . $date_string . ':income')/10000,2);
            }
            $total = round($query->sum('amount') / 10000, 2);

            return view('userFooter', compact('total', 'income'));
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->inviteName('邀请人账号')->as(function (){
            return $this->user ? $this->user->username : '-';
        });
        $show->bank_card_number('银行卡');
        $show->bank('开户行');
        $show->mode('防封模式')->as(function ($mode){
            return $mode ? '开启': '关闭';
        });
        $show->encryption('通道加密')->as(function ($encryption){
            return $encryption ? '加密': '关闭';
        });
        $show->status('冻结')->as(function ($status){
            return $status ? '冻结': '关闭';
        });
        $show->total_income_amount('总收益');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);

        $form->text('username', '账号');
        $form->text('real_name', '真实姓名');
        $form->text('bank_card_number', '银行卡卡号');
        $form->text('bank', '开户行');
        $form->number('one_day_max_send_count', '当日最大发送数');
        $form->switch('mode', '是否开启防封模式')->states([
            'on' => ['text' => '开启'],
            'off' => ['text' => '关闭'],
        ]);
        $form->switch('encryption', '通道是否加密')->states([
            'on' => ['text' => '加密'],
            'off' => ['text' => '关闭'],
        ]);
        $form->switch('status', '是否冻结')->states([
            'on' => ['text' => '冻结'],
            'off' => ['text' => '关闭'],
        ]);
        $form->password('password', '密码')->rules('required|confirmed')
            ->default(function ($form) {
                return $form->model()->password;
            });
        $form->password('password_confirmation', '确认密码')->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });
        $form->ignore(['password_confirmation']);
        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
        });

        return $form;
    }

    public function taskHistory($id, Content $content)
    {
        $taskHistory = TaskHistory::where('user_id', $id);

        return $content
            ->header('添加会员')
            ->description('')
            ->body(view('taskHistory', compact('taskHistory')));
    }

    public function invite()
    {
        $users = User::with(['users' => function($query){
            $query->whereBetween('created_at', [Carbon::today()->firstOfMonth(), Carbon::today()->lastOfMonth()]);
        }])->select('id', 'username', 'real_name')->get();

        $users = $users->map(function ($user){
            return [
                'id' => $user->id,
                'username' => $user->username,
                'real_name' => $user->real_name,
                'count' => $user->users->count()
            ];
        });

        return $users->sortByDesc('count')->take(50)->toArray();
    }

    public function amount()
    {
        $users = User::with('taskHistories')->get();
        $users = $users->map(function ($user){
            return [
                'id' => $user->id,
                'username' => $user->username,
                'real_name' => $user->real_name,
                'amount' => $user->taskHistories->sum('amount'),
            ];
        });

        return $users->sortByDesc('amount')->take(50)->toArray();
    }
}
