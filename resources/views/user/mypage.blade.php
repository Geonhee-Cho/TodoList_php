@extends('layout')
@section('content')
    <?php
    $login_id = session()->get('id');
    ?>
    @if(!isset($login_id))
        <script>
            alert("로그인이 필요합니다.");
            location.href = '{{route("userSignin")}}';
        </script>
    @else
        <script>
            $(function (){
                $('.btn_drop_user').click(function (){
                    if(!confirm("회원탈퇴하시겠습니까?")){
                        return;
                    }
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: 'DELETE',
                        url: '{{ route('drop') }}',
                        dataType: 'json',
                        data: { },
                        success: function (data) {
                            if(data["statusCode"] != 200){
                                alert("회원 탈퇴 실패 : "+data['message']);
                            }else{
                                alert("회원 탈퇴 되었습니다.");
                                location.href='{{route("index")}}';
                            }
                        },
                        error: function (data) {
                            alert("회원 탈퇴 실패 : "+data['message']);
                        }
                    });
                });
            });
        </script>
        <table>
            <tr>
                <td>ID : {{$userInfo -> user_id}}</td>
            </tr>
            <tr>
                <td>name : {{$userInfo -> name}}</td>
            </tr>
            <tr>
                <td>memo count: {{$memoCount}}</td>
            </tr>
            <tr>
                <td>
                    <button class="btn_drop_user ">회원탈퇴</button>
                </td>
            </tr>
        </table>
    @endif
@endsection
