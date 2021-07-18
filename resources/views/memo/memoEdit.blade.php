@extends("layout")
@section("content")
    <?php
    $login_id = session()->get('id');
    ?>
    @if(!isset($login_id))
        <script>
            alert("로그인이 필요합니다.");
            location.href = '{{route("userSignin")}}';
        </script>
    @else
    <form>
        <table>
            <tr>
                <td>날짜</td>
                <td>
                    @if(isset($memo -> id))
                        {{$memo->event_date}}
                    @else
                        <input type="date" name="event_date" value="{{$memo -> event_date}}">
                    @endif
                </td>
                <td><p id="event_date_msg" class="check-msg" style="color: red; margin: 0;"></p></td>
            </tr>
            <tr>
                <td>시간</td>
                <td>
                    @if(isset($memo -> id))
                        {{$memo->event_time}}:00~{{$memo->event_time+1}}:00
                    @else
                        <select name="st_event_time">
                            @for($i = 0; $i < 24; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                        ~
                        <select name="end_event_time">
                            @for($i = 0; $i < 24; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    @endif
                </td>
                <td><p id="st_event_time_msg" class="check-msg" style="color: red; margin: 0;"></p></td>
                <td><p id="end_event_time_msg" class="check-msg" style="color: red; margin: 0;"></p></td>
            </tr>
            <tr>
                <td>제목</td>
                <td><input type="text" name="title" value="{{$memo -> title}}"></td>
                <td><p id="title_msg" class="check-msg" style="color: red; margin: 0;"></p></td>
            </tr>
            <tr>
                <td>내용</td>
                <td>
                    <textarea name="content" rows="4">{{$memo -> content}}</textarea>
                </td>
                <td><p id="content_msg" class="check-msg" style="color: red; margin: 0;"></p></td>
            </tr>
        </table>
    </form>
    @if(isset($memo -> id))
        <button class="btn-patch">edit</button>
    @else
        <button class="btn-submit">submit</button>
    @endif
    <script>
        $(function (){
            $('.btn-submit').click(function (){
                let event_date = $("input[name=event_date]").val();
                let st_event_time = $("select[name=st_event_time]").val();
                let end_event_time = $("select[name=end_event_time]").val();
                let title = $("input[name=title]").val();
                let content = $("textarea[name=content]").val();

                if(!showCheckMsg({
                    event_date : validationCheck(event_date,['required'], "날짜"),
                    st_event_time : validationCheck(st_event_time,['required'], "시작시간"),
                    end_event_time : validationCheck(end_event_time,['required'], "종료시간"),
                    title : validationCheck(title,['required'], "제목"),
                    content : validationCheck(content,['required'], "내용"),
                })) return;

                if(st_event_time > end_event_time){
                    showCheckMsg({st_event_time : "날짜를 확인해주세요."});
                    return;
                }

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    url: '{{ route('postMemo') }}',
                    dataType: 'json',
                    data: {
                        event_date : event_date,
                        st_event_time : st_event_time,
                        end_event_time : end_event_time,
                        title : title,
                        content : content
                    },
                    success: function (data) {
                        if(data["statusCode"] != 200){
                            showCheckMsg(data['message']);
                        }else{
                            alert("등록 완료")
                            location.href='{{route("memoList")}}';
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

            $('.btn-patch').click(function (){
                let id = '{{$memo -> id}}';
                let title = $("input[name=title]").val();
                let content = $("textarea[name=content]").val();

                if(!showCheckMsg({
                    title : validationCheck(title,['required'], "제목"),
                    content : validationCheck(content,['required'], "내용"),
                })) return;

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'PATCH',
                    url: '{{ route('patchMemo') }}',
                    dataType: 'json',
                    data: {
                        id : id,
                        title : title,
                        content : content
                    },
                    success: function (data) {
                        if(data["statusCode"] != 200){
                            showCheckMsg(data['message']);
                        }else{
                            alert("수정 완료")
                            location.href='{{route('memoDetail')}}?memo_id={{$memo -> id}}';
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
    @endif
@endsection
