<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_total_amount')->default(0)->comment('用户总收益');
            $table->unsignedBigInteger('card_total_deduction')->default(0)->comment('账号总扣款');
            $table->date('date')->nullable()->comment('日期');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_reports');
    }
}
