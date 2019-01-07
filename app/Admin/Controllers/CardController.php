<?php

namespace App\Admin\Controllers;

use App\Recharge;
use App\Task;
use Carbon\Carbon;
use DB;
use Validator;
use App\Admin\Extensions\CardExpoter;
use App\Card;
use App\Http\Controllers\ResponseController;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class CardController extends ResponseController
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
        $grid->password('密码');
        $grid->amount('金额');
        $grid->status('状态')->switch([
            'on' => ['text' => '封卡'],
            'off' => ['text' => '正常'],
        ]);
        $grid->created_at('添加时间');
        $grid->updated_at('更新时间');

        $grid->filter(function ($filter){
            $filter->disableIdFilter();
            $filter->between('name', '账号');
        });

        $grid->tools(function ($tools){
            $tools->append('<a class="btn btn-sm btn-default" href="'.url('/admin/add-account').'">批量导入账号</a>');
            $tools->append('<a class="btn btn-sm btn-default" href="'.url('/admin/add-account-amount').'">会员卡批量充值</a>');
            $tools->append('<a class="btn btn-sm btn-default" href="'.url('/admin/account-amount-search').'">会员卡批量查询</a>');
        });

        $grid->actions(function ($actions){
            $actions->disableDelete();
        });
        $grid->disableExport();
        $grid->disableRowSelector();

        $excel = new CardExpoter();
        $excel->setAttr(
            ['账号', '密码', '余额'],
            ['name', 'password']
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
        $form->switch('status', '状态')->states([
            'on' => ['text' => '封卡'],
            'off' => ['text' => '正常'],
        ]);
        $form->text('password', '密码')->rules('required');

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
        });

        return $form;
    }

    public function addAccount(Content $content)
    {
        return $content
            ->header('批量导入账号')
            ->description('')
            ->body('<import-cards></import-cards>');
    }

    public function addAccountAmount(Content $content)
    {
        return $content
            ->header('会员卡批量充值')
            ->description('')
            ->body('<add-account-amount></add-account-amount>');
    }

    public function saveAccountAmount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_name' => 'required|number20',
            'end_name' => 'required|number20',
            'amount' => 'required|numeric',
        ]);
        if($validator->fails()){
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }
        $start_name = $request->input('start_name');
        $end_name = $request->input('end_name');
        $start_name_start = substr($start_name, 0, 16);    //开始前缀
        $start_name_end = intval(substr($start_name, 16, 4));    //开始尾数
        $end_name_end = intval(substr($end_name, 16, 4));    //结束尾数
        if($start_name_end + 1000 <= $end_name_end){
            return $this->setStatusCode(422)->responseError('单次只能为1000个账号充值');
        }
        for ($i = $start_name_end; $i <= $end_name_end; $i++) {
            $names[] = $start_name_start.str_pad($i,4,"0",STR_PAD_LEFT);
        }
        $cards = Card::whereIn('name', $names)->get();
        if (count($cards) != count($names)) {
            return $this->setStatusCode(422)->responseError('存在不连续的用户名');
        }

        $amount = intval($request->input('amount') * 10000);
        $now = Carbon::now()->toDateTimeString();

        DB::beginTransaction(); //开启事务
        $res = Card::whereIn('id', $cards->pluck('id'))->increment("amount",$amount);
        $recharge = $cards->map(function ($card) use($amount, $now){
            return [
                'card_id' => $card->id,
                'amount' => $amount,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        });
        Recharge::insert($recharge->toArray());
        DB::commit();   //保存

        return $this->responseSuccess('对'.$res.'条记录各增加了'.($amount / 10000).'元');
    }

    public function importCards(Request $request)
    {
        $file = $request->file('file');
        if (!in_array($file->getClientOriginalExtension(), ['xlsx', 'xls'])) {
            return $this->setStatusCode(422)->responseError('上传文件格式错误');
        }

        $data = collect(Excel::load($file)->get()->toArray())->map(function ($item){
            return [
                'name' => $item[0],
                'password' => $item[1],
                'amount' => $item[2],
            ];
        });
        $data->shift();

        return $this->responseSuccess($data);
    }

    public function saveImportCards(Request $request)
    {
        $request->validate([
            'adds' => 'required',
        ]);

        $adds = collect($request->input('adds'));
        $adds_count = $adds->count();
        $adds_unique = $adds->pluck('name')->unique()->count();
        if($adds_count != $adds_unique){
            return $this->setStatusCode(422)->responseError('存在重复的卡号请修改后重新上传！');
        }

        $date = date('Y-m-d H:i:s', time());
        try {
            $adds = $adds->map(function ($item) use ($date) {

                if(!preg_match('/^\d{20}$/', $item['name'])){
                    throw new Exception($item['name'].'卡号有误请检查');
                }

                return [
                    'name' => $item['name'],
                    'password' => $item['password'],
                    'amount' => $item['amount'] * 10000,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            });
        }catch (Exception $e){
            return $this->setStatusCode(422)->responseError($e->getMessage());
        }


        try {
            $card = Card::insert($adds->toArray());

            return $this->responseSuccess($card);
        }catch (\Exception $e){
            return $this->setStatusCode(422)->responseError('出错啦，可能系统已存在即将导入的账号，请仔细核对！');
        }
    }

    public function accountAmountSearch(Content $content)
    {
        return $content
            ->header('会员卡批量查询')
            ->description('')
            ->body('<account-amount-search></account-amount-search>');
    }

    public function saveAccountAmountSearch(Request $request)
    {
        $file = $request->file('file');
        if (!in_array($file->getClientOriginalExtension(), ['xlsx', 'xls'])) {
            return $this->setStatusCode(422)->responseError('上传文件格式错误');
        }

        $data = collect(Excel::load($file)->get()->toArray())->map(function ($item, $index=0){
            if($index == 0){
                $index++;
                return [$item[0], $item[1], $item[2]];
            }

            $res = Card::where('name', $item[0])->first();
            $status = '密码错误';
            if($res && $res->password == $item[1]){
                $status = '匹配成功';
            }

            $index++;
            return ["\t".$item[0], "\t".$item[1], $status == '匹配成功' ? $res->amount : '匹配失败',];
        });

        Excel::create(time().random_int(1000, 9999),function($excel) use ($data){
            $excel->sheet('Sheet1', function($sheet) use ($data){
                $sheet->rows($data);
            });
        })->export('xlsx');
    }
}
