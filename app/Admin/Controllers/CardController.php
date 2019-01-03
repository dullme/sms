<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\CardExpoter;
use App\Card;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CardController extends Controller
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
    public function index(Content $content)
    {
        return $content
            ->header('卡类管理')
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
            ->body($this->form(true));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Card);

        $grid->id('ID');
        $grid->name('账号');
        $grid->amount('金额');
        $grid->created_at('添加时间');
        $grid->updated_at('更新时间');

        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->between('name', '账号');
        });

        $grid->tools(function ($tools){
            $tools->append('<a class="btn btn-sm btn-default" href="'.url('/admin/add-account').'">批量导入账号</a>');
            $tools->append('<a class="btn btn-sm btn-default" href="'.url('/admin/add-account-amount').'">会员卡批量充值</a>');
        });

        $excel = new CardExpoter();
        $excel->setAttr(
            ['账号', '密码'],
            ['name', 'real_password']
        );
        $grid->exporter($excel);

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
        $show = new Show(Card::findOrFail($id));

        $show->id('Id');
        $show->name('账号');
        $show->amount('金额');
        $show->password('密码');
        $show->created_at('添加时间');
        $show->updated_at('更新时间');

        return $show;
    }

    /**
     * @param bool $show
     * @return Form
     */
    protected function form($show = false)
    {
        $form = new Form(new Card);

        if($show){
            $form->text('name', '卡号')->rules('required|unique:cards|number20');
            $form->currency('amount', '金额')->symbol('￥')->rules('required|numeric');
        }else{
            $form->display('name', '卡号')->rules('required|unique:cards|number20');
            $form->display('amount', '金额')->rules('required|numeric');
        }
        $form->text('password', '密码')->rules('required');

        return $form;
    }

    public function addAccount(Content $content)
    {
        return $content
            ->header('批量导入账号')
            ->description('')
            ->body(view('addAccount'));
    }

    public function addAccountAmount(Content $content)
    {
        return $content
            ->header('会员卡批量充值')
            ->description('')
            ->body('<meter-reading></meter-reading>');
    }
}
