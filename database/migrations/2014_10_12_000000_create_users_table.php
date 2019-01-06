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
            $table->string('real_name')->comment('真实姓名');
            $table->string('password')->comment('密码');
            $table->string('bank_card_number')->comment('银行卡卡号');
            $table->string('bank')->comment('开户行');
            $table->integer('amount')->default(0)->comment('余额10000');
            $table->integer('one_day_max_send_count')->comment('当日最大发送条数');
            $table->boolean('mode')->default(0)->comment('开启防封模式0:关闭;1:开启');
            $table->boolean('encryption')->default(0)->comment('通道加密0:不加密;1:加密');
            $table->boolean('status')->default(0)->comment('冻结状态0:不冻结;1:冻结');
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
