<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop\Entity\Merchandise;
use App\Shop\Entity\User;
use App\Shop\Entity\Transaction;
use DB;
use Validator;
use Image;

/*
首頁 / GET MerchandiseController@indexPage
商品清單檢視 /merchandise GET MerchandiseController@merchandiseListPage
商品管理清單檢視 /merchandise/manage GET MerchandiseController@merchandiseManageListPage
商品資料新增 /merchandise/create GET MerchandiseController@merchandiseCreateProcess
商品單品檢視 /merchandise/{merchandise_id} GET MerchandiseController@merchandiseItemPage
商品單品編輯頁面檢視 /merchandise/{merchandise_id}/edit GET MerchandiseController@merchandiseItemEditPage
商品單品資料修改 /merchandise/{merchandise_id} PUT MerchandiseController@merchandiseItemUpdateProcess
購買商品 /merchandise/{merchandise_id}/buy POST MerchandiseController@merchandiseItemBuyProcess
*/

class MerchandiseController extends Controller
{
    //首頁
    public function indexPage()
    {
        return redirect('/merchandise');
    }

    //新增商品
    public function merchandiseCreateProcess(){
        $merchandise_data=[
            'status'=>'C', //商品建立中
            'name'=>'',
            'name_en'=>'',
            'introduction'=>'',
            'introduction_en'=>'',
            'photo'=>null,
            'price'=>0,
            'remain_count'=>0,
        ];

        $Merchandise=Merchandise::create($merchandise_data);

        return redirect('/merchandise/'.$Merchandise->id.'/edit');
    }

    //編輯商品
    public function merchandiseItemEditPage($merchandise_id)
    {
        $Merchandise=Merchandise::findOrFail($merchandise_id);

        if(!is_null($Merchandise->photo)){
            $Merchandise->photo=url($Merchandise->photo);
        }

        $binding=[
            'title'=>trans('shop.merchandise.edit'),
            'Merchandise'=>$Merchandise,
        ];

        return view('merchandise.editMerchandise',$binding);
    }

    public function merchandiseItemUpdateProcess($merchandise_id)
    {
        $Merchandise=Merchandise::findOrFail($merchandise_id);
        $input=request()->all();

        $rules=[
            'status'=>[
                'required',
                'in:C,S',
            ],
            'name'=>[
                'required',
                'max:80',
            ],
            'name_en'=>[
                'required',
                'max:80',
            ],
            'introduction'=>[
                'required',
                'max:2000',
            ],
            'introduction_en'=>[
                'required',
                'max:2000',
            ],
            'photo'=>[
                'file',
                'image',
                'max:10240', //10M
            ],
            'price'=>[
                'required',
                'integer',
                'min:0',
            ],
            'remain_count'=>[
                'required',
                'integer',
                'min:0',
            ],
        ];

        $validator=Validator::make($input,$rules);

        if($validator->fails()){
            return redirect('/merchandise/'.$Merchandise->id.'/edit')
            ->withErrors($validator)
            ->withInput();
        }

        if(isset($input['photo'])){
            $photo=$input['photo'];
            $file_extension=$photo->getClientOriginalExtension();
            $file_name=uniqid().'.'.$file_extension;
            $file_relative_path='images/merchandise/'.$file_name;
            $file_path=public_path($file_relative_path);
            $image=Image::make($photo)->fit(450,300)->save($file_path);
            $input['photo']=$file_relative_path;
        }

        $Merchandise->update($input);

        return redirect('/merchandise/'.$Merchandise->id.'/edit');
    }

    //後台:列出商品
    public function merchandiseManageListPage()
    {
        $row_per_page=10;

        $MerchandisePaginate=Merchandise::OrderBy('created_at','desc')
            ->paginate($row_per_page);
        
        foreach($MerchandisePaginate as &$Merchandise){
            if(!is_null($Merchandise->photo)){
                $Merchandise->photo=url($Merchandise->photo);
            }
        }

        $binding=[
            'title'=>trans('shop.merchandise.manage'),
            'MerchandisePaginate'=>$MerchandisePaginate,
        ];

        return view('merchandise.manageMerchandise',$binding);
    }

    //前台:列出商品
    public function merchandiseListPage()
    {
        $row_per_page=10;

        $MerchandisePaginate=Merchandise::OrderBy('updated_at','desc')
            ->where('status','S')
            ->paginate($row_per_page);

        foreach($MerchandisePaginate as &$Merchandise){
            if(!is_null($Merchandise->photo)){
                $Merchandise->photo=url($Merchandise->photo);
            }
        }

        $binding=[
            'title'=>trans('shop.merchandise.list'),
            'MerchandisePaginate'=>$MerchandisePaginate,
        ];

        return view('merchandise.listMerchandise',$binding);
    }

    //前台:列出單項商品
    public function merchandiseItemPage($merchandise_id)
    {
        $Merchandise=Merchandise::findOrFail($merchandise_id);

        if(!is_null($Merchandise->photo)){
            $Merchandise->photo=url($Merchandise->photo);
        }

        $binding=[
            'title'=>trans('shop.merchandise.page'),
            'Merchandise'=>$Merchandise,
        ];

        return view('merchandise.showMerchandise',$binding);
    }

    //購買商品
    public function merchandiseItemBuyProcess($merchandise_id)
    {
        $input=request()->all();

        $rules=[
            'buy_count'=>[
                'required',
                'integer',
                'min:1',
            ],
        ];

        $validator=Validator::make($input,$rules);

        if($validator->fails()){
            return redirect('/merchandise/'.$merchandise_id)
                ->withErrors($validator)
                ->withInput();
        }

        try{
            $user_id=session()->get('user_id');
            $User=User::findOrFail($user_id);

            DB::beginTransaction();

            $Merchandise=Merchandise::findOrFail($merchandise_id);
            
            $buy_count=$input['buy_count'];

            $remain_count_after_buy=$Merchandise->remain_count-$buy_count;

            if($remain_count_after_buy<0){
                throw new Exception('商品數量不足，無法購買。');
            }
            
            $Merchandise->remain_count=$remain_count_after_buy;
            $Merchandise->save();

            $total_price=$buy_count*$Merchandise->price;

            $transaction_data=[
                'user_id'=>$User->id,
                'merchandise_id'=>$Merchandise->id,
                'price'=>$Merchandise->price,
                'buy_count'=>$buy_count,
                'total_price'=>$total_price,
            ];

            Transaction::create($transaction_data);

            DB::commit();

            $message=[
                'msg'=>[
                    trans('shop.merchandise.purchase-success'),
                ],
            ];

            return redirect()
                ->to('/merchandise/'.$Merchandise->id)
                ->withErrors($message);

        }catch(Exception $exception){
            DB::rollBack();

            $error_message=[
                'msg'=>[
                    $exception->getMessage(),
                ],
            ];

            return redirect()
                ->back()
                ->withErrors($error_message)
                ->withInput();
        }
    }

    //test:比較isset,empty,is_null
    public function xxxmerchandiseItemPage($merchandise_id)
    {
        // header("Content-Type:text/html; charset=utf-8");
    
        function check($value){
            if(isset($value)){
                echo "isset()判定值有設置<br />";
            }else{
                echo "isset()判定值未設置<br />";
            }
        
            if(empty($value)){
                echo "empty()判定未有值<br />";
            }else{
                echo "empty()判定有值<br />";
            }
            
            if(is_null($value)){
                echo "is_null()判定未有值<br />";
            }else{
                echo "is_null()判定有值<br />";
            }
        }
        
        echo "設定value1值為字串<hr />";
        $value1='test';
        check($value1);
        echo "<br />";
        
        echo "設定value1值為null<hr />";
        $value1=null;
        check($value1);
        echo "<br />";
        
        echo "設定value1值為空陣列<hr />";
        $value1=array();
        check($value1);
        echo "<br />";
        
        echo "註銷value1值<hr />";
        unset($value1);
        @check($value1);
        echo "<br />";
        
        echo "設定value1值為0<hr />";
        $value1="0";
        check($value1);
    }
}
