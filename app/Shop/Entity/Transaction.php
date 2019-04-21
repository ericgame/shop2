<?php

namespace App\Shop\Entity;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = "transaction";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "user_id",
        "merchandise_id",
        "price",
        "buy_count",
        "total_price",
    ];

    //Eloquent：關聯
    public function Merchandise()
    {
        //Transaction的merchandise_id 對應 Merchandise的id
        return $this->hasOne('App\Shop\Entity\Merchandise', 'id', 'merchandise_id');
    }
}
