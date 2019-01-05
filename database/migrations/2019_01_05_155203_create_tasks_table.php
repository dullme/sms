<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content');
            $table->string('price');
            $table->string('status')->default('UNDONE')->comment('UNDONE:未完成;COMPLETED:已完成');
            $table->boolean('running')->default(true)->comment('是否进行中');
            $table->integer('count')->comment('任务总数量');
            $table->integer('finished')->default(0)->comment('已完成任务数量');
            $table->longText('mobile')->comment('手机号');
            $table->longText('finished_mobile')->nullable()->comment('已发送手机号');
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
        Schema::dropIfExists('tasks');
    }
}
