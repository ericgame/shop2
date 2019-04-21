<!DOCTYPE html>
<html>
<head>
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
    <title>Ajax: Form資料傳送與處理</title>
</head>
<body>
    <h3>Ajax: Form資料傳送與處理</h3>
    <form id="demo">
        @csrf
        <p>暱稱：<input type="text" name="nickname" id="nickname"></p>
        <p>性別：
        <select name="gender" id="gender">
            <option value="">請選擇</option>
            <option value="男">男</option>
            <option value="女">女</option>
        </select>
        </p>
        <button type="button" id="submitExample">送出</button>
    </form>
    <br>
    <p id="result"></p> <!-- 顯示回傳資料 -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> <!-- 引入 jQuery -->
    <script>
    $(document).ready(function() {
        $("#submitExample").click(function() { //ID 為 submitExample 的按鈕被點擊時
            $.ajax({
                type: "POST", //傳送方式
                url: "ajax3", //傳送目的地
                dataType: "json", //資料格式
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // }, //可用data中的_token取代
                // data: { //傳送資料
                //     _token:  '{{csrf_token()}}', //可取代headers的X-CSRF-TOKEN設定
                //     nickname: $("#nickname").val(), //表單欄位 ID nickname
                //     gender: $("#gender").val() //表單欄位 ID gender
                // },
                data: $("#demo").serialize(),
                success: function(data) {
                    if (data.nickname) { //如果後端回傳 json 資料有 nickname
                        // $("#demo")[0].reset(); //重設 ID 為 demo 的 form (表單)
                        $("#result").html('<font color="#007500">您的暱稱為「<font color="#0000ff">' + data.nickname + '</font>」，性別為「<font color="#0000ff">' + data.gender + '</font>」！</font>');
                    } else { //否則讀取後端回傳 json 資料 errorMsg 顯示錯誤訊息
                        $("#demo")[0].reset(); //重設 ID 為 demo 的 form (表單)
                        $("#result").html('<font color="#ff0000">' + data.errorMsg + '</font>');
                    }
                },
                error: function(jqXHR) {
                    $("#demo")[0].reset(); //重設 ID 為 demo 的 form (表單)
                    $("#result").html('<font color="#ff0000">發生錯誤：' + jqXHR.status + '</font>');
                }
            })
        })
    });
    </script>
</body>
</html>