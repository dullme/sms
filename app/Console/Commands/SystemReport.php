<?php

namespace App\Console\Commands;

use App\UserDailyRevenue;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SystemReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生产系统报表';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user_daily_revenue = UserDailyRevenue::where('date', Carbon::today())->get();
        $system_report = \App\SystemReport::where('date', Carbon::today())->first();

        if($system_report){
            $system_report->user_total_amount = $user_daily_revenue->sum('total_income_amount');
            $system_report->card_total_deduction = $user_daily_revenue->sum('total_charged_amount');
            $system_report->save();
        }else{
            \App\SystemReport::create([
                'user_total_amount' => $user_daily_revenue->sum('total_income_amount'),
                'card_total_deduction' => $user_daily_revenue->sum('total_charged_amount'),
                'date' => Carbon::today()
            ]);
        }
    }
}
