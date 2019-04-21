<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(){
        $value = [
            "data" => "test123zz",
        ];

        // return 'test';
        return view('test.test', $value);
    }

    public function modelTest(){
        return 'modelTest';
    }
}

/*
controller
model
view

*/
