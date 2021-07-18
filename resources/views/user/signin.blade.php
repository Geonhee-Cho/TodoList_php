@extends('layout')
@section('content')
    <script>
        $(function (){
            $('.btn-submit').click(function (){
                let user_id = $("input[name=user_id]").val();
                let password = $("input[name=password]").val();
                if(!showCheckMsg({user_id : validationCheck(user_id,['required', 'min:5', 'max:20'], "ID"),
                    password : validationCheck(password,['required', 'min:8', 'max:20'], "비밀번호"),
                })) return;

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    url: '{{ route('userSigninCheck') }}',
                    dataType: 'json',
                    data: {
                        user_id : user_id,
                        password : password
                    },
                    success: function (data) {
                        if(data["statusCode"] != 200){
                            showCheckMsg(data['message']);
                        }else{
                            alert(data['user_name']+"님 환영합니다!");
                            location.href='{{route("index")}}';
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
        </table>
    </form>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <button class="btn-submit">submit</button>
@endsection
