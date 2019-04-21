<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\Warning;
use Mail;

class WarningController extends Controller
{
    public function send(){
        //收件者務必使用 collect 指定二維陣列，每個項目務必包含 "name", "email"
        // $to = collect([
        //     ['name' => 'eric', 'email' => 'ericarc99@gmail.com']
        // ]);
        $to = 'ericarc99@gmail.com';

        //提供給view的參數
        $params = [
            'say' => '測試內容'
        ];

        //內容顯示在瀏覽器(不送出email)
        // echo (new Warning($params))->render(); die;

        //送出email
        Mail::to($to)->send(new Warning($params));
    }
}
