<?php

namespace App\Admin\Controllers;

use App\SystemReport;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class SystemReportController extends Controller
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
            ->header('系统报表')
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
    protected function grid()
    {
        $grid = new Grid(new SystemReport);

        $grid->id('ID');
        $grid->user_total_amount('用户总收益')->display(function ($value){
            return $value /10000;
        });
        $grid->card_total_deduction('账号总扣款')->display(function ($value){
            return $value /10000;
        });
        $grid->date('日期');

        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->between('date', '日期')->datetime();
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
        $show = new Show(SystemReport::findOrFail($id));

        $show->id('Id');
        $show->user_total_amount('User total amount');
        $show->card_total_deduction('Card total deduction');
        $show->date('Date');
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
        $form = new Form(new SystemReport);

        $form->number('user_total_amount', 'User total amount');
        $form->number('card_total_deduction', 'Card total deduction');
        $form->date('date', 'Date')->default(date('Y-m-d'));

        return $form;
    }
}
