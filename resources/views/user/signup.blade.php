@extends('layout')
@section('content')
    <script>
        $(function (){
            $('.btn-submit').click(function (){
                let user_id = $("input[name=user_id]").val();
                let password = $("input[name=password]").val();
                let confirm_password = $("input[name=confirm_password]").val();
                let name = $("input[name=name]").val();

                if(!showCheckMsg({user_id : validationCheck(user_id,['required', 'min:5', 'max:20'], "ID"),
                        password : validationCheck(password,['required', 'min:8', 'max:20'], "비밀번호"),
                        confirm_password : validationCheck(confirm_password,['required', 'min:8', 'max:20'], "비밀번호 확인"),
                        name : validationCheck(name,['required', 'min:2', 'max:10'], "이름")
                    })) return;

                if(password != confirm_password){
                    showCheckMsg({confirm_password : "비밀번호 값이 일치하지 않습니다."});
                    return;
                }

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    url: '{{ route('userStore') }}',
                    dataType: 'json',
                    data: {
                        user_id : user_id,
                        password : password,
                        confirm_password : confirm_password,
                        name : name
                    },
                    success: function (data) {
                        if(data["statusCode"] != 200){
                            showCheckMsg(data['message']);
                        }else{
                            if(confirm("가입이 완료되었습니다!\n로그인 페이지로 이동하시겠습니까?")){
                                location.href='{{route("userSignin")}}';
                            }else{
                                location.href='{{route("index")}}';
                            }
                        }
                    },
                    error: function (data) {
                        if(data.status == 422){
                            showCheckMsg(data.responseJSON.errors);
                        }else{
                            alert("서버 에러입니다. 관리자에게 문의해주세요.");
                        }
                    }
                });
            });
        });
    </script>
    {{--<form method="post" action="{{route('userStore')}}">--}}
    <form>
       {{-- @csrf--}}
        <table>
            <tr>
                <td>ID</td>
            </tr>
            <tr>
                <td><input type="text" name="user_id" value=""/></td>
                <td><p id="user_id_msg" class="check-msg" style="color: red; margin: 0;"></p></td>
            </tr>
            <tr>
                <td>PW</td>
            </tr>
            <tr>
                <td><input type="password" name="password" value=""/></td>
                <td><p id="password_msg" class="check-msg" style="color: red; margin: 0;"></p></td>
            </tr>
            <tr>
                <td>Confirm PW</td>
            </tr>
            <tr>
                <td><input type="password" name="confirm_password" value=""/></td>
                <td><p id="confirm_password_msg" class="check-msg" style="color: red; margin: 0;"></p></td>
            </tr>
            <tr>
                <td>NAME : </td>
            </tr>
            <tr>
                <td><input type="text" name="name" value=""/></td>
                <td><p id="name_msg" class="check-msg" style="color: red; margin: 0;"></p></td>
            </tr>
        </table>
    </form>
    <button class="btn-submit">submit</button>
@endsection
