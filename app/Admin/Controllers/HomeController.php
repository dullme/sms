<?php

namespace App\Admin\Controllers;

use App\Card;
use App\Http\Controllers\Controller;
use App\Recharge;
use App\Task;
use App\User;
use Carbon\Carbon;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\InfoBox;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $res = Recharge::where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow())->get();
        $user_count = User::where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow())->count();
        $task_count =Task::where('running', 1)->count();
        $card_count = Card::where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow())->count();

        $user = new UserController();
        $invites = $user->invite();
        $amounts = $user->amount();

        return $content
            ->header('首页')
            ->description('')
            ->row(function (Row $row)use ($user_count, $res, $task_count, $card_count){
                $row->column(3, new InfoBox('当日新增用户', 'users', 'aqua', '/admin/user', $user_count));
                $row->column(3, new InfoBox('当日充值总金额', 'cny', 'green', '/admin/recharge', $res->sum('amount')));
                $row->column(3, new InfoBox('当日新增卡', 'credit-card-alt', 'red', '/admin/card', $card_count));
                $row->column(3, new InfoBox('进行中的任务', 'pie-chart', 'yellow', '/admin/task', $task_count));
            })
            ->row(function (Row $row) use ($res, $invites, $amounts) {

                $row->column(6, function (Column $column) use($invites) {
                    $column->append(view('invite', compact('invites')));
                });

                $row->column(6, function (Column $column) use($amounts) {
                    $column->append(view('amount', compact('amounts')));
                });

            });
    }
}
