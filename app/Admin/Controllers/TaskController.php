<?php

namespace App\Admin\Controllers;

use App\Task;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class TaskController extends Controller
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
            ->header('任务列表')
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
            ->header('任务详情')
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
            ->header('任务编辑')
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
            ->header('添加任务')
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
        $grid = new Grid(new Task);

        $grid->id('ID');
        $grid->content('任务内容');
        $grid->price('任务单价');
        $grid->status('状态')->display(function ($status){
            $color = array_get(Task::$color, $status);
            $status = array_get(Task::$status, $status);
            return "<span class='badge bg-$color'>$status</span>";
        });
        $states = [
            'on'  => ['value' => 1, 'text' => '开启', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '暂停', 'color' => 'default'],
        ];
        $grid->running('是否开启')->switch($states);
        $grid->count('任务总数');
        $grid->finished('已完成数');
        $grid->created_at('添加时间');

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
        $show = new Show(Task::findOrFail($id));

        $show->id('Id');
        $show->content('Content');
        $show->price('Price');
        $show->status('Status');
        $show->count('Count');
        $show->finished('Finished');
        $show->mobile('Mobile');
        $show->finished_mobile('Finished mobile');
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
        $form = new Form(new Task);

        $form->text('content', 'Content');
        $form->text('price', 'Price');
        $form->text('status', 'Status')->default('UNDONE');
        $form->number('count', 'Count');
        $form->number('finished', 'Finished');
        $form->textarea('mobile', 'Mobile');
        $form->textarea('finished_mobile', 'Finished mobile');

        return $form;
    }
}
