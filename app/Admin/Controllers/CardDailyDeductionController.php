<?php

namespace App\Admin\Controllers;

use App\Card;
use App\CardDailyDeduction;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CardDailyDeductionController extends Controller
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
        $card = Card::findOrFail($id);
        return $content
            ->header($card->name)
            ->description('剩余金额 '. $card->amount)
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
        $grid = new Grid(new CardDailyDeduction);
        $grid->model()->where('card_id', $id)->orderBy('date', 'DESC')->orderBy('created_at', 'DESC');
        $grid->id('ID');
        $grid->total_charged_amount('当日扣除总额');
        $grid->date('日期');
        $grid->created_at('创建时间');

        $grid->filter(function ($filter) {
            $filter->between('date', '日期')->date();
        });

//                $grid->disableTools();
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
        $show = new Show(CardDailyDeduction::findOrFail($id));

        $show->id('Id');
        $show->card_id('Card id');
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
        $form = new Form(new CardDailyDeduction);

        $form->number('card_id', 'Card id');
        $form->number('total_charged_amount', 'Total charged amount');
        $form->date('date', 'Date')->default(date('Y-m-d'));

        return $form;
    }
}
