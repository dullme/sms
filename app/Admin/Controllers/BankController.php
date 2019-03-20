<?php

namespace App\Admin\Controllers;

use App\Bank;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class BankController extends Controller
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
            ->header('银行管理')
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
            ->header('详情')
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
            ->header('编辑')
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
            ->header('新增')
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
        $grid = new Grid(new Bank);

        $grid->id('ID');
        $grid->name('银行名称');
        $grid->type('类型')->switch([
            'on'  => ['text' => '二维码'],
            'off' => ['text' => '银行卡'],
        ]);
        $grid->created_at('创建时间');
        $grid->disableExport();
        $grid->disableFilter();
        $grid->disableTools();

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
        $show = new Show(Bank::findOrFail($id));

        $show->id('ID');
        $show->name('银行名称');
        $show->type('类型')->as(function ($type){
            return $type ? '二维码' : '银行卡';
        });
        $show->created_at('创建时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Bank);

        $form->text('name', '银行名称');
        $form->switch('type', '类型')->states([
            'on'  => ['text' => '二维码'],
            'off' => ['text' => '银行卡'],
        ]);

        return $form;
    }
}
