<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;
use DB;
use App\User;
use App\Withdraw;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class WithdrawController extends Controller
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
            ->header('提现管理')
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
            ->header('提现详情')
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
        Withdraw::where('status', 0)->findOrFail($id);

        return $content
            ->header('编辑提现')
            ->description('')
            ->body($this->form()->edit($id));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'status' => 'required',
            'remark' => 'required_if:status,9',
        ]);

        $data = [
            'status' => $request->get('status'),
            'remark' => $request->get('remark', ''),
            'payment_at' => $request->get('payment_at', Carbon::now()),
        ];

        DB::transaction(function () use ($id, $data) {
            $withdraw = Withdraw::where('status', 0)->findOrFail($id);
            if ($data['status'] == 9) {
                $data['remark'] .= '(已退回余额)';
                User::where('id', $withdraw->user_id)->increment('amount', ($withdraw->amount * 10000));
            }

            return $withdraw->update($data);

        });

        return redirect()->to('/admin/withdraw');
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
            ->header('新建提现')
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
        $grid = new Grid(new Withdraw);
        $grid->model()->orderBy('status');
        $grid->id('ID')->sortable();
        $grid->column('user.username', '用户账号');
        $grid->column('user.real_name', '用户姓名');
        $grid->amount('提现金额');
        $grid->status('状态')->display(function ($status) {
            $color = array_get(Withdraw::$colors, $status);
            $status = array_get(Withdraw::$status, $status);

            return "<span class='badge bg-$color'>$status</span>";
        })->sortable();
        $grid->remark('备注');
        $grid->payment_at('提现时间')->sortable();
        $grid->created_at('申请时间')->sortable();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('user.username', '用户账号');
            $filter->scope('status', '待处理')->where('status', 0);
        });
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
            if ($actions->row->status != 0)
                $actions->disableEdit();
        });
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableCreateButton();

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
        $show = new Show(Withdraw::findOrFail($id));

        $show->id('Id');
        $show->user_id('用户ID');
        $show->amount('提现金额');
        $show->status('状态');
        $show->remark('备注');
        $show->payment_at('提现时间');
        $show->created_at('申请时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Withdraw);

        $form->display('user.username', '用户账号');
        $form->display('user.real_name', '用户姓名');
        $form->display('amount', '提现金额');
        $form->radio('status', '提现状态')->options([0 => '待处理', 1 => '提现成功', 9 => '提现失败']);
        $form->text('remark', '备注')->rules('required_if:status,9');
        $form->datetime('payment_at', '提现时间')->default(date('Y-m-d H:i:s'));
        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
            // 去掉`查看`按钮
            $tools->disableView();
        });

        $form->footer(function ($footer) {
            // 去掉`查看`checkbox
            $footer->disableViewCheck();

            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();

            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();

        });

        return $form;
    }
}
