<html>
<head>
<title>Laravel Ajax</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script>
function getMessage(){
   $.ajax({
      type:'get',
      url:'getmsg',
      data:'_token = {{csrf_token()}}',
      success:function(data){
         $("#msg").html(data.msg);
      }
   });
}

</script>
</head>
<body>
   <div id="msg">xxxxxx</div>
   <p><button onclick="getMessage()">透過 Ajax 傳送訊息</button></p>
</body>
</html>

