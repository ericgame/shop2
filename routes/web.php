<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//首頁
Route::get('/','MerchandiseController@indexPage');

//使用者
Route::group(['prefix' => 'user'], function(){
    //使用者驗證
    Route::group(['prefix' => 'auth'], function(){
        Route::get('/sign-up', 'UserAuthController@signUpPage');
        Route::post('/sign-up', 'UserAuthController@signUpProcess');
        Route::get('/sign-in', 'UserAuthController@signInpage');
        Route::post('/sign-in', 'UserAuthController@signInProcess');
        Route::get('/sign-out', 'UserAuthController@signOut');
        //Facebook登入
        Route::get('/facebook-sign-in', 'UserAuthController@facebookSignProcess');
        //Facebook登入重新導向授權資料處理
        Route::get('/facebook-sign-in-callback', 'UserAuthController@facebookSignInCallbackProcess');
    });
});

//商品
Route::group(['prefix' => 'merchandise'], function(){
    Route::get('/','MerchandiseController@merchandiseListPage');
    Route::get('/manage','MerchandiseController@merchandiseManageListPage')
        ->middleware(['user.auth.admin']);
    Route::get('/create','MerchandiseController@merchandiseCreateProcess')
        ->middleware(['user.auth.admin']);
    //指定商品
    Route::group(['prefix' => '{merchandise_id}'], function(){
        Route::get('/','MerchandiseController@merchandiseItemPage')
            ->where([
                'merchandise_id' => '[0-9]+',
            ]);
        Route::group(['middleware' => ['user.auth.admin']], function(){
            Route::get('/edit','MerchandiseController@merchandiseItemEditPage');
            Route::put('/','MerchandiseController@merchandiseItemUpdateProcess');
        });
        Route::post('/buy','MerchandiseController@merchandiseItemBuyProcess')
            ->middleware(['user.auth']);
    });
});

//交易
Route::get('/transaction','TransactionController@transactionListPage')
    ->middleware(['user.auth']);

//test
Route::get('/test','TestController@test');

//寄email
Route::get('/warning','WarningController@send');

//Ajax: 顯示資料
Route::get('ajax','AjaxController@ajaxView');
Route::get('getmsg','AjaxController@ajaxData');

//Ajax: Form資料傳送與處理
Route::get('ajax2','AjaxController@ajaxFormView');
Route::post('ajax3','AjaxController@ajaxFormData');

