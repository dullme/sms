<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('用户ID');
            $table->integer('task_id')->unsigned()->comment('任务ID');
            $table->string('ip')->comment('设备IP');
            $table->string('iccid')->comment('集成电路卡识别码');
            $table->string('imsi')->comment('国际移动用户识别码');
            $table->string('status')->comment('扣费状态0:失败;1:成功');
            $table->unsignedBigInteger('amount')->comment('金额');
            $table->string('mobile')->comment('发送手机号');
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('task_histories');
    }
}
