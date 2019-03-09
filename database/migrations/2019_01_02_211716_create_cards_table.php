<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('卡号');
            $table->integer('user_id')->nullable()->unsigned()->comment('最后一次使用该卡的用户');
            $table->unsignedBigInteger('amount')->default(0)->comment('金额');
            $table->unsignedBigInteger('monday')->default(0)->comment('星期一');
            $table->unsignedBigInteger('tuesday')->default(0)->comment('星期二');
            $table->unsignedBigInteger('wednesday')->default(0)->comment('星期三');
            $table->unsignedBigInteger('thursday')->default(0)->comment('星期四');
            $table->unsignedBigInteger('friday')->default(0)->comment('星期五');
            $table->unsignedBigInteger('saturday')->default(0)->comment('星期六');
            $table->unsignedBigInteger('sunday')->default(0)->comment('星期天');
            $table->string('password')->comment('密码');
            $table->boolean('status')->default(0)->comment('封卡0:正常;1:封卡');
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
        Schema::dropIfExists('cards');
    }
}
