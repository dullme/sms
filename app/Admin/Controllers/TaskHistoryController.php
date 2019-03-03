<?php

namespace App\Admin\Controllers;

use App\TaskHistory;
use App\Http\Controllers\Controller;
use App\User;
use Session;
use Carbon\Carbon;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class TaskHistoryController extends Controller
{
    use HasResourceActions, AdminControllerTrait;

    /**
     * CardController constructor.
     */
    public function __construct()
    {
        $this->loadVue();
    }

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

    public function deletePage(Content $content)
    {
        return $content
            ->header('删除历史记录')
            ->description('')
            ->body(view('deleteTaskHistory'));
    }

    public function deleteTaskHistory(Request $request)
    {
        $request->validate([
            'days' => 'required|integer'
        ]);

        $day = Carbon::today()->subDays($request->input('days'));

        $delete_task_histories = TaskHistory::where('created_at', '<=', $day)->delete();

        Session::flash('deleteTaskHistories', "共删除了{$delete_task_histories}条记录");

        return back();
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($id)
    {
        $grid = new Grid(new TaskHistory);
        $grid->model()->where('user_id', $id)->orderBy('created_at', 'DESC');
        $grid->id('ID');
        $grid->ip('IP');
        $grid->iccid('ICCID');
        $grid->imsi('IMSI');
        $grid->status('状态')->display(function ($status){
            $color = array_get(TaskHistory::$colors,$status);
            $status = array_get(TaskHistory::$status,$status);
            return "<span class='badge bg-$color'>$status</span>";
        });

        $grid->status_code('备注')->display(function ($status_code){
            return array_get(TaskHistory::$status_code, $status_code);
        });
        $grid->mobile('接收号码');
        $grid->column('task.content', '接收内容');
        $grid->created_at('接收时间');
        $grid->send_at('实际时间');

        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->between('send_at', '实际时间')->datetime();
        });

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
