<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - shop2</title>
    <script src="/assets/js/jquery-2.2.4.min.js" defer></script>
    <script src="/assets/js/bootstrap.min.js" defer></script>
    <script src="/assets/js/js.cookie.js" defer></script>
    <script src="/assets/js/shop-laravel.js" defer></script>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/css/shop_laravel.css">
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Shop Laravel</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="/">{{ trans('shop.home') }}</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="#">
                        <span class="set_language" data-language="zh-TW">
                            中文
                        </span>
                        /
                        <span class="set_language" data-language="en">
                            English
                        </span>
                    </a>
                </li>
                @if(session()->has('user_id'))
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ trans('shop.transaction.name') }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/transaction">{{ trans('shop.transaction.list') }}</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ trans('shop.merchandise.name') }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/merchandise/create">{{ trans('shop.merchandise.create') }}</a></li>
                            <li><a href="/merchandise/manage">{{ trans('shop.merchandise.manage') }}</a></li>
                        </ul>
                    </li>
                    <li><a href="/user/auth/sign-out">{{ trans('shop.auth.sign-out') }}</a></li>
                @else
                    <li><a href="/user/auth/sign-in">{{ trans('shop.auth.sign-in') }}</a></li>
                    <li><a href="/user/auth/facebook-sign-in">{{ trans('shop.auth.facebook-sign-in') }}</a></li>
                    <li><a href="/user/auth/sign-up">{{ trans('shop.auth.sign-up') }}</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    @yield('content')
</div>
</body>
</html>