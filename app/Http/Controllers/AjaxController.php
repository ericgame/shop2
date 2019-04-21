<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    //Ajax: 顯示資料
    public function ajaxView(){
        return view('message');
    }

    public function ajaxData(){
        $msg="Ajax 您好 !!";
        return response()->json(['msg'=> $msg]);
    }

    //Ajax: Form資料傳送與處理
    public function ajaxFormView(){
        return view('message2');
    }

    public function ajaxFormData(){
        $nickname = $_POST["nickname"];
        $gender = $_POST["gender"];

        return response()->json(['nickname'=> $nickname,'gender'=> $gender]);
    }
}
