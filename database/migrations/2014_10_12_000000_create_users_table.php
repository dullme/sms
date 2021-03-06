<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->unsigned()->default(0)->comment('推介人ID');
            $table->integer('invite')->unsigned()->default(0)->comment('邀请人数');
            $table->string('username')->unique();
            $table->string('real_name')->nullable()->comment('真实姓名');
            $table->string('password')->comment('密码');
            $table->string('withdraw_password')->nullable()->comment('提现密码');
            $table->string('bank_card_number')->nullable()->comment('银行卡卡号');
            $table->string('bank')->nullable()->comment('开户行');
            $table->string('alipay')->nullable()->comment('支付宝二维码');
            $table->unsignedBigInteger('amount')->default(0)->comment('余额10000');
            $table->unsignedBigInteger('total_income_amount')->default(0)->comment('总收益');
            $table->integer('one_day_max_send_count')->default(0)->comment('当日最大发送条数，为0时取配置表里的数据');
            $table->boolean('mode')->default(0)->comment('开启防封模式0:关闭;1:开启');
            $table->boolean('encryption')->default(0)->comment('通道加密0:不加密;1:加密');
            $table->boolean('status')->default(0)->comment('冻结状态0:不冻结;1:冻结');
            $table->string('security_question')->comment('密保问题');
            $table->string('classified_answer')->comment('密保答案');
            $table->string('code')->comment('邀请码');
            $table->string('baud_rate')->default('1200')->comment('波特率');
            $table->integer('wrong_password')->default(0)->comment('密码错误次数');
            $table->string('country')->nullable()->comment('国家ICCID');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
