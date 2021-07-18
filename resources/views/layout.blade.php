<?php
    $login_id = session()->get('id');
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-salce=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>To Do List</title>
</head>
<link rel="stylesheet" href="//unpkg.com/bootstrap@4/dist/css/bootstrap.min.css">
<script src='//unpkg.com/jquery@3/dist/jquery.min.js'></script>
<script src='//unpkg.com/popper.js@1/dist/umd/popper.min.js'></script>
<script src='//unpkg.com/bootstrap@4/dist/js/bootstrap.min.js'></script>
<script>
    <?php
        $message = isset($_GET['alert_message']) ? $_GET['alert_message'] : null;
        ?>

    if('{{$message}}' != ''){
        alert('{{$message}}');
        history.replaceState({}, null, location.pathname);
    }

    function showCheckMsg(data){
        let chkArray = Object.keys(data);
        let returnValue = true;
        let checkMsg = document.getElementsByClassName("check-msg");
        for(let i = 0; i < checkMsg.length; i++){
            checkMsg[i].innerText = "";
        }
        for(let i = 0; i < chkArray.length; i++){
            if(data[chkArray[i]] != null){
                document.getElementById(chkArray[i]+"_msg").innerText = data[chkArray[i]];
                returnValue = false;
            }
        }

        return returnValue;
    }

    function validationCheck(checkValue, checkRules, msgVar){
        for(let i=0; i<checkRules.length; i++){
            if(checkRules[i] == "required"){
                if (checkValue == ""){
                    return msgVar+" 항목은 필수 항목입니다.";
                }
            }else if(checkRules[i].indexOf("min:") >= 0){
                let arr = checkRules[i].split(":");
                if (checkValue.length < arr[arr.length-1]){
                    return msgVar+" 값이 "+arr[arr.length-1]+" 글자 이상으로 작성하셔야합니다.";
                }
            }else if(checkRules[i].indexOf("max:") >= 0){
                let arr = checkRules[i].split(":");
                if (checkValue.length > arr[arr.length-1]){
                    return msgVar+" 값이 "+arr[arr.length-1]+" 글자보다 많습니다.";
                }
            }
        }
    }

    function signOut(){
        if(confirm("로그아웃 하시겠습니까?")){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'DELETE',
                url: '{{ route('userSignOut') }}',
                dataType: 'json',
                data: { },
                success: function (data) {
                    if(data["statusCode"] != 200){
                        alert(data['message']);
                    }else{
                        location.href='{{route("index")}}';
                    }
                },
                error: function (data) {
                    alert("로그아웃 실패 : "+data['message']);
                }
            });
        }
    }
</script>
<style>
    ::-webkit-scrollbar{
        display: none;
    }
</style>
<body>
<div style="width: 100%; height: auto; overflow-y: auto;">
    <div style="width: 100%;height: 50px;border-bottom: 1px solid darkgray;">
        <h2 style="cursor: pointer; display: inline-block" onclick="location.href='{{route('index')}}'">To Do List</h2>
        @if(isset($login_id))
            <a style="float: right" href="javascript:signOut()">Sign Out</a>
            <span style="float: right">&nbsp;|&nbsp;</span>
            <a style="float: right" href="{{route('myPage')}}">My Page</a>
        @else
            <a style="float: right" href="{{route('userCreate')}}">Sign Up</a>
            <span style="float: right">&nbsp;|&nbsp;</span>
            <a style="float: right" href="{{route('userSignin')}}">Sign In</a>
        @endif
    </div>
    <div style="width: 100%;height: 800px;">
        <div style="width: 50%;height: 100%; margin: 0 auto; background: #f0f0f0;">
            @yield('content')
        </div>
    </div>
    <div style="width: 100%;height: 50px;border-top: 1px solid darkgray;">
    </div>
</div>
</body>
</html>
