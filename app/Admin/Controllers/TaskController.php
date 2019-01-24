<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\ResponseController;
use App\Task;
use Maatwebsite\Excel\Facades\Excel;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class TaskController extends ResponseController
{
    use HasResourceActions, AdminControllerTrait;

    /**
     * TaskController constructor.
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
            ->body('<add-task></add-task>');
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
        $grid->amount('任务单价');
        $grid->column('jindu', '进度')->display(function (){
            $res = round(100-(100/$this->count * $this->unfinished), 2);

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
        $show->amount('任务单价');
        $show->status('状态');
        $show->count('任务总数');
        $show->unfinished('未完成数');
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
        $form->currency('amount', '任务单价');
        $form->switch('running', '是否开启	')->states([
            'on'  => ['value' => 1, 'text' => '开启', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '暂停', 'color' => 'default'],
        ]);

        return $form;
    }

    public function taskAdd(Request $request)
    {
        $request->validate([
            'price' => 'required|numeric',
            'content' => 'required',
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        if (!in_array($file->getClientOriginalExtension(), ['xlsx', 'xls'])) {
            return $this->setStatusCode(422)->responseError('上传文件格式错误');
        }

        $data = Excel::load($file)->get()->toArray();

        foreach ($data as $item){
            $mobile = (string)($item[0]);
            if(strlen($mobile)!= 11){
                return $this->setStatusCode(422)->responseError($mobile.'该号码有误');
            }
            $res[] = $mobile;
        }
        $res = implode(',', $res);

        $task = Task::create([
            'content' => $request->input('content'),
            'amount' => $request->input('price'),
            'count' => (strlen($res) + 1)/12,
            'mobile' => $res,
        ]);

        return $this->responseSuccess($task);
    }
}
