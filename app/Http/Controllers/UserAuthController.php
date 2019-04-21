<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
Use Hash;
use App\Shop\Entity\User;
use Mail;

class UserAuthController extends Controller
{
    /*
    使用者註冊頁面 /user/auth/sign-up GET UserAuthController@signUpPage
    使用者資料新增 /user/auth/sign-up POST UserAuthController@signUpProcess
    使用者登入頁面 /user/auth/sign-in GET UserAuthController@signInpage
    使用者登入處理 /user/auth/sign-in POST UserAuthController@signInProcess
    使用者登出 /user/auth/sign-out GET UserAuthController@signOut
    */

    //會員註冊
    public function signUpPage(){
        $binding = [
            'title' => trans('shop.auth.sign-up'),
        ];

        return view('auth.signUp', $binding);
    }

    public function signUpProcess()
    {
        $input = request()->all();

        $rules = [
            'nickname' => [
                'required',
                'max:50',
            ],
            'email' => [
                'required',
                'max:150',
                'email',
            ],
            'password' => [
                'required',
                'same:password_confirmation',
                'min:6',
            ],
            'password_confirmation' => [
                'required',
                'min:6',
            ],
            'type' => [
                'required',
                'in:G,A',
            ],
        ];

        $validator = Validator::make($input, $rules);

        if($validator->fails()){
            return redirect('/user/auth/sign-up')
                ->withErrors($validator)
                ->withInput();
        }

        $input['password'] = Hash::make($input['password']);

        $Users = User::Create($input);

        $mail_binding = [
            'nickname' => $input['nickname'],
            'email' => $input['email'],
        ];

        //寄出email
        // SendSignUpMailJob::dispatch($mail_binding)->onQueue('high');

        //寄送email: 純文字內容
        // Mail::raw('測試 Mailgun 寄信服務', function($message){
        //     $message->from('eric@eric.com', 'eric');
        //     $message->to('ericarc99@gmail.com');
        //     $message->subject('測試 Mailgun');
        // });

        //寄送email: html內容
        $mail_binding = [
            'nickname' => $input['nickname']
            // 'nickname' => 'eric'
        ];

        Mail::send('email.signUpEmailNotification', $mail_binding,
        function($message) use ($input){
        // function($message){
            $message->from('eric@eric.com', 'eric');
            $message->to($input['email']);
            // $message->to('ericarc99@gmail.com');
            $message->subject('註冊成功');
        });

        return redirect('/user/auth/sign-in');
    }

    //會員登入
    public function signInPage()
    {
        $binding=[
            "title"=>trans('shop.auth.sign-in'),
        ];

        return view('auth.signIn',$binding);
    }

    public function signInProcess()
    {
        $input=request()->all();

        $rules=[
            'email'=>[
                'required',
                'max:150',
                'email',
            ],
            'password'=>[
                'required',
                'min:6',
            ],
        ];

        $validator=Validator::make($input,$rules);

        if($validator->fails()){
            return redirect('/user/auth/sign-in')
                ->withErrors($validator)
                ->withInput();
        }

        $User=User::where('email',$input['email'])->firstOrFail();

        $is_password_correct=Hash::check($input['password'],$User->password);

        if(!$is_password_correct){
            $error_message=[
                'msg'=>[
                    '密碼驗證錯誤',
                ],
            ];

            return redirect('/user/auth/sign-in')
                ->withErrors($error_message)
                ->withInput();
        }

        session()->put('user_id',$User->id);

        return redirect()->intended('/');
    }

    //會員登出
    public function signOut()
    {
        session()->forget('user_id');

        return redirect('/user/auth/sign-in');
    }
}
