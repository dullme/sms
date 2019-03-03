<?php

namespace App\Admin\Controllers;

use App\User;
use App\UserDailyRevenue;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UserDailyRevenueController extends Controller
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
        $grid = new Grid(new UserDailyRevenue);
        $grid->model()->where('user_id', $id)->orderBy('created_at', 'DESC');
        $grid->id('ID');
        $grid->user()->username('用户账号');
        $grid->total_income_amount('当日总收入')->display(function ($total_income_amount){
            return $total_income_amount /10000;
        });
        $grid->total_charged_amount('当日总支出')->display(function ($total_charged_amount){
            return $total_charged_amount /10000;
        });
        $grid->date('日期');


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
        $show = new Show(UserDailyRevenue::findOrFail($id));

        $show->id('Id');
        $show->user_id('User id');
        $show->total_income_amount('Total income amount');
        $show->total_charged_amount('Total charged amount');
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
        $form = new Form(new UserDailyRevenue);

        $form->number('user_id', 'User id');
        $form->number('total_income_amount', 'Total income amount');
        $form->number('total_charged_amount', 'Total charged amount');
        $form->date('date', 'Date')->default(date('Y-m-d'));

        return $form;
    }
}
