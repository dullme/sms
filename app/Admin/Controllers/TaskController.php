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
        $grid->column('jindu', '进度')->display(function (){
            $res = round(100/$this->count * $this->finished, 2);

           return '<div class="progress" style="min-width: 100px"><div class="progress-bar progress-bar-striped active" role="progressbar" style="color: #333;width: '.$res.'%;">'.$res.'%</div></div>';
        });
        $grid->created_at('添加时间');

        $grid->running('是否开启')->switch([
            'on'  => ['value' => 1, 'text' => '开启', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '暂停', 'color' => 'default'],
        ]);


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

        $show->id('ID');
        $show->content('任务内容');
        $show->price('任务单价');
        $show->status('状态');
        $show->count('任务总数');
        $show->finished('已完成数');
        $show->created_at('添加时间');

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

        $form->text('content', '任务内容');
        $form->currency('price', '任务单价');
        $form->switch('running', '是否开启	')->states([
            'on'  => ['value' => 1, 'text' => '开启', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '暂停', 'color' => 'default'],
        ]);

        return $form;
    }
}
