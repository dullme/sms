<?php

namespace App\Admin\Controllers;

use App\TaskHistory;
use App\Http\Controllers\Controller;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class TaskHistoryController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index($id, Content $content)
    {
        $user = User::findOrFail($id);
        return $content
            ->header($user->username)
            ->description($user->real_name)
            ->body($this->grid($id));
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
            ->header('Detail')
            ->description('description')
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
            ->header('Edit')
            ->description('description')
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
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($id)
    {
        $grid = new Grid(new TaskHistory);
        $grid->model()->where('user_id', $id);
        $grid->id('ID');
        $grid->ip('IP');
        $grid->iccid('ICCID');
        $grid->imsi('IMSI');
        $grid->status('状态')->display(function ($status){
            $color = array_get(TaskHistory::$colors,$status);
            $status = array_get(TaskHistory::$status,$status);
            return "<span class='badge bg-$color'>$status</span>";
        });
        $grid->mobile('接收号码');
        $grid->column('task.content', '接收内容');
        $grid->created_at('接收时间');
        $grid->disableActions();
        $grid->disableExport();
        $grid->disableTools();
        $grid->disableCreateButton();
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
        $show = new Show(TaskHistory::findOrFail($id));

        $show->id('Id');
        $show->user_id('User id');
        $show->task_id('Task id');
        $show->ip('Ip');
        $show->iccid('Iccid');
        $show->imsi('Imsi');
        $show->status('Status');
        $show->mobile('Mobile');
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
        $form = new Form(new TaskHistory);

        $form->number('user_id', 'User id');
        $form->number('task_id', 'Task id');
        $form->ip('ip', 'Ip');
        $form->text('iccid', 'Iccid');
        $form->text('imsi', 'Imsi');
        $form->text('status', 'Status');
        $form->mobile('mobile', 'Mobile');

        return $form;
    }
}
