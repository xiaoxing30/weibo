<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //在 up 方法里面，我们通过调用 Schema 类的 create 方法来创建 users 表：
        //create 方法会接收两个参数：一个是数据表的名称，另一个则是接收 $table（Blueprint 实例）的闭包。
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',60);
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
        //down 方法会在回滚命令发起时被调用，是 up 方法的逆向操作。
        Schema::dropIfExists('users');
    }

    //当我们运行迁移时，up 方法会被调用；
    //当我们回滚迁移时，down 方法会被调用。
}
