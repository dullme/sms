<?php

namespace App\Admin\Controllers;

use App\SendLog;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class SendLogController extends Controller
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
        $grid = new Grid(new SendLog);
        $grid->model()->where('user_id', $id)->where('created_at','>=', Carbon::today())->orderBy('created_at', 'DESC');
        $grid->id('ID');
        $grid->send_log('历史日志')->display(function ($send_log){
            $str = explode(';', $send_log);
            return implode($str, '<br/>');
        });
        $grid->created_at('记录时间');

//        $grid->disableTools();
        $grid->disableActions();
        $grid->disableExport();
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
        $show = new Show(SendLog::findOrFail($id));

        $show->id('Id');
        $show->user_id('User id');
        $show->send_log('Send log');
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
        $form = new Form(new SendLog);

        $form->number('user_id', 'User id');
        $form->text('send_log', 'Send log');

        return $form;
    }
}
