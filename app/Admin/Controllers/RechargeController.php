<?php

namespace App\Admin\Controllers;

use App\Recharge;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class RechargeController extends Controller
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
            ->header('充值记录')
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
        $grid = new Grid(new Recharge);
        $grid->model()->orderBy('created_at', 'desc');
        $grid->id('ID');
        $grid->column('card.name', '卡号');
        $grid->amount('金额');
        $grid->created_at('充值时间')->sortable();

        $grid->disableActions();
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();

        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->like('card.name', '卡号');
            $filter->between('created_at', '充值时间')->datetime();
        });

        $grid->footer(function ($query) {
            $total = round($query->sum('amount') / 10000, 2);

            return view('rechargeFooter', compact('total'));
        });

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
        $show = new Show(Recharge::findOrFail($id));

        $show->id('Id');
        $show->card_id('Card id');
        $show->amount('Amount');
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
        $form = new Form(new Recharge);

        $form->number('card_id', 'Card id');
        $form->number('amount', 'Amount');

        return $form;
    }
}
