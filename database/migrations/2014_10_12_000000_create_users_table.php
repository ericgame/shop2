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
    /*
    *使用者(user):
    說明, 欄位名稱, 欄位屬性, 備註

    會員編號 id integer (primary key, auto increment)
    Email email varchar(150) (unique key)
    密碼 password varchar(60)
    帳號類型 type varchar(1) (A:Admin 管理者)(G:General 一般會員:預設值)
    暱稱 nickname varchar(50)
    建立時間 created_at datetime
    更新時間 updated_at datetime
    */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 150)->unique();
            $table->string('password', 60);
            $table->string('type', 1)->default('G');
            $table->string('nickname', 50);
            $table->timestamps();
            $table->unique(['email'], 'user_email_uk');
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
