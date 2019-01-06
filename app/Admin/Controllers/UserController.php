<?php

namespace App\Admin\Controllers;

use App\TaskHistory;
use App\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

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
        $grid->column('user.username', '邀请人账号')->display(function (){
            return $this->user ? $this->user->username : '-';
        });
        $grid->invite('已邀请人数')->sortable();
        $grid->username('账号');
        $grid->real_name('姓名');
        $grid->bank_card_number('银行卡');
        $grid->bank('开户行');
        $grid->amount('余额');
        $grid->one_day_max_send_count('当日最大发送数');
        $grid->mode('防封模式')->switch([
            'on' => ['text' => '开启'],
            'off' => ['text' => '关闭'],
        ]);
        $grid->encryption('通道加密')->switch([
            'on' => ['text' => '加密'],
            'off' => ['text' => '关闭'],
        ]);
        $grid->status('冻结')->switch([
            'on' => ['text' => '冻结'],
            'off' => ['text' => '关闭'],
        ]);
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

        $show->id('Id');
        $show->pid('Pid');
        $show->invite('Invite');
        $show->username('Username');
        $show->real_name('Real name');
        $show->password('Password');
        $show->bank_card_number('Bank card number');
        $show->bank('Bank');
        $show->amount('Amount');
        $show->one_day_max_send_count('One day max send count');
        $show->mode('Mode');
        $show->encryption('Encryption');
        $show->status('Status');
        $show->remember_token('Remember token');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

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
}
