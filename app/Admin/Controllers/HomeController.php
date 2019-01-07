<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Recharge;
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

        return $content
            ->header('首页')
            ->description('')
            ->row(function (Row $row) use ($res) {

                $row->column(3, new InfoBox('当日充值总金额', 'cny', 'aqua', '/admin/recharge', $res->sum('amount')));
            });
    }
}
