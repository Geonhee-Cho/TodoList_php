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
    @elseif($login_id != $memo->user_id_no)
        <script>
            alert("잘못된 접근입니다.");
            location.href = '{{route("memoList")}}';
        </script>
    @else
    <h4>{{$memo->event_date}} {{$memo->event_time}}:00~{{$memo->event_time+1}}:00</h4>
    <h2>{{$memo->title}}</h2>
    <p>{{$memo->content}}</p>
    <input type="button" class="btn-list" value="list" onclick="location.href='{{route("memoList")}}?sch_event_date='+'{{$memo -> event_date}}'">
    <input type="button" class="btn-edit" value="edit" onclick="location.href='{{route("memoEdit")}}?memo_id='+'{{$memo -> id}}'">
    <input type="button" class="btn-delete" value="delete">
    <script>
        $(function (){
            $('.btn-delete').click(function (){
                if(!confirm("메모를 삭제하시겠습니까?")){
                    return;
                }

                let memo_id = {{$memo -> id}};

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'DELETE',
                    url: '{{ route('deleteMemo') }}',
                    dataType: 'json',
                    data: {
                        memo_id : memo_id
                    },
                    success: function (data) {
                        alert("삭제 완료")
                        location.href='{{route("memoList")}}?sch_event_date='+'{{$memo -> event_date}}';
                    },
                    error: function (data) {
                        alert("삭제 실패 : "+data['message']);
                    }
                });
            });
        });
    </script>
    @endif
@endsection
