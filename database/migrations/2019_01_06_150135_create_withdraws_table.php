<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('外键：users的主键');
            $table->unsignedBigInteger('amount')->comment('申请金额10000');
            $table->unsignedBigInteger('balance')->comment('扣之前的金额10000');
            $table->string('bank_card_number')->nullable()->comment('银行卡卡号');
            $table->string('bank')->nullable()->comment('开户行');
            $table->boolean('status')->default(0)->comment('处理状态0:未处理;1:已处理');
            $table->string('remark')->nullable()->comment('备注');
            $table->timestamp('payment_at')->nullable()->comment('支付日期');
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
        Schema::dropIfExists('withdraws');
    }
}
