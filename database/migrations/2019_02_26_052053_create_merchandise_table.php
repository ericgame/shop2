<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchandiseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /*
    *商品(merchandise):
    說明, 欄位名稱, 欄位屬性, 備註

    商品編號 id integer (primary key, auto increment)
    商品狀態 status varchar(1) (C:Create 建立中:預設值)(S:Sell 可販售)(視需要可加 D:Discontinued 停產)
    商品名稱 name varchar(80)
    商品英文名稱 name_en varchar(80)
    商品介紹 introduction text
    商品英文介紹 introduction_en text
    商品照片 photo varchar(50)
    商品價格 price integer
    商品剩餘數量 remain_count integer
    建立時間 created_at datetime
    更新時間 updated_at datetime
    */
    public function up()
    {
        Schema::create('merchandise', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status', 1)->default('C');
            $table->string('name', 80)->nullable();
            $table->string('name_en', 80)->nullable();
            $table->text('introduction');
            $table->text('introduction_en');
            $table->string('photo', 50)->nullable();
            $table->integer('price');
            $table->integer('remain_count');
            $table->timestamps();
            $table->index(['status'], 'merchandise_satus_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchandise');
    }
}
