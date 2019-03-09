<?php

namespace App\Console\Commands;

use App\Card;
use App\CardDailyDeduction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class StoreCardAmount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'card:amount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '保存每天的卡记录';

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

        if(Carbon::today()->isSunday()){
            $today = 'sunday';
            Card::query()->update([
                'monday' => 0,
                'tuesday' => 0,
                'wednesday' => 0,
                'thursday' => 0,
                'friday' => 0,
                'saturday' => 0,
            ]);
        }elseif (Carbon::today()->isMonday()){
            $today = 'monday';
            Card::query()->update([
                'sunday' => 0,
                'tuesday' => 0,
                'wednesday' => 0,
                'thursday' => 0,
                'friday' => 0,
                'saturday' => 0,
            ]);
        }elseif (Carbon::today()->isTuesday()){
            $today = 'tuesday';
            Card::query()->update([
                'sunday' => 0,
                'monday' => 0,
                'wednesday' => 0,
                'thursday' => 0,
                'friday' => 0,
                'saturday' => 0,
            ]);
        }elseif (Carbon::today()->isWednesday()){
            $today = 'wednesday';
            Card::query()->update([
                'sunday' => 0,
                'monday' => 0,
                'tuesday' => 0,
                'thursday' => 0,
                'friday' => 0,
                'saturday' => 0,
            ]);
        }elseif (Carbon::today()->isThursday()){
            $today = 'thursday';
            Card::query()->update([
                'sunday' => 0,
                'monday' => 0,
                'tuesday' => 0,
                'wednesday' => 0,
                'friday' => 0,
                'saturday' => 0,
            ]);
        }elseif (Carbon::today()->isFriday()){
            $today = 'friday';
            Card::query()->update([
                'sunday' => 0,
                'monday' => 0,
                'tuesday' => 0,
                'wednesday' => 0,
                'thursday' => 0,
                'saturday' => 0,
            ]);
        }elseif (Carbon::today()->isSaturday()){
            $today = 'saturday';
            Card::query()->update([
                'sunday' => 0,
                'monday' => 0,
                'tuesday' => 0,
                'wednesday' => 0,
                'thursday' => 0,
                'friday' => 0,
            ]);
        }

        if(isset($today)){
            $cards = Card::all();
            $now = Carbon::now();
            $date = $now->toDateString();
            $cards = $cards->map(function ($card)use ($today, $date, $now){
                return [
                    'card_id' => $card['id'],
                    'total_charged_amount' => $card[$today],
                    'date' => $date,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->toArray();

            $this->storeComplex($cards);
        }

    }


    /**
     * 保存到数据库
     * @param array $data
     */
    public function storeComplex(array $data) {
        $count = count($data);
        if($count / 1000 >=1){
            $count = intval($count / 1000) + 1;
        }

        $data = collect($data)->split($count);

        $data->map(function($item){
            CardDailyDeduction::insert($item->toArray());
        });
    }
}
