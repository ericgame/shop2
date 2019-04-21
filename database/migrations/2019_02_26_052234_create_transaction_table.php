<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /*
    *交易紀錄(transaction):
    說明, 欄位名稱, 欄位屬性, 備註

    交易編號 id integer (primary key, auto increment)
    使用者編號 user_id integer
    商品編號 merchandise_id integer
    當時購買單價 price integer
    購買數量 buy_count integer
    交易總價格 total_price integer
    建立時間 created_at datetime
    更新時間 updated_at datetime
    */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('merchandise_id');
            $table->integer('price');
            $table->integer('buy_count');
            $table->integer('total_price');
            $table->timestamps();
            $table->index(['user_id'], 'user_transaction_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction');
    }
}
