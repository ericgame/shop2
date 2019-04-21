<!-- 檔案目錄：resources/views/transaction/listUserTransaction.blade.php -->

<!-- 指定繼承 layout.master 母模板 -->
@extends('layout.master')

<!-- 傳送資料到母模板，並指定變數為 title -->
@section('title', $title)

<!-- 傳送資料到母模板，並指定變數為 content -->
@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>

        {{-- 錯誤訊息模板元件 --}}
        @include('components.validationErrorMessage')

        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <tr>
                        <th>{{ trans('shop.merchandise.fields.name') }}</th>
                        <th>{{ trans('shop.merchandise.fields.photo') }}</th>
                        <th>{{ trans('shop.merchandise.fields.price') }}</th>
                        <th>{{ trans('shop.transaction.fields.amount') }}</th>
                        <th>{{ trans('shop.transaction.fields.total') }}</th>
                        <th>{{ trans('shop.transaction.fields.date') }}</th>
                    </tr>
                    @foreach($TransactionPaginate as $Transaction)
                        <tr>
                            <td>
                                <a href="/merchandise/{{ $Transaction->Merchandise->id }}">
                                    {{ $Transaction->Merchandise->name }}
                                </a>
                            </td>
                            <td>
                                <a href="/merchandise/{{ $Transaction->Merchandise->id }}">
                                    <!-- <img src="{{ $Transaction->Merchandise->photo or '/assets/images/default-merchandise.png' }}" /> -->
                                    <img src="{{isset($Transaction->Merchandise->photo) ? $Transaction->Merchandise->photo : '/assets/images/default-merchandise.png'}}" />
                                </a>
                            </td>
                            <td>{{ $Transaction->price }}</td>
                            <td>{{ $Transaction->buy_count }}</td>
                            <td>{{ $Transaction->total_price }}</td>
                            <td>{{ $Transaction->created_at }}</td>
                        </tr>
                    @endforeach
                </table>

                {{-- 分頁頁數按鈕 --}}
                {{ $TransactionPaginate->links() }}
            </div>
        </div>
    </div>
@endsection