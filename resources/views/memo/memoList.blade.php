@extends('layout')
@section('content')
    <?php
    $login_id = session()->get('id');
    ?>
    @if(!isset($login_id))
{{--        <script>--}}
{{--            alert("로그인이 필요합니다.");--}}
{{--            location.href = '{{route("userSignin")}}';--}}
{{--        </script>--}}
    @else
        <div style="width: 100%;height: 100%;float: left;padding: 5px;box-sizing: border-box;">
            <input type="date" name="sch_event_date" id="sch_event_date" value="{{$sch_event_date}}" class="btn-block" onchange="location.href='{{route('memoList')}}?sch_event_date='+this.value">
            <div style="overflow-y: auto; height: 712px;">
                <table width="100%" style="border-collapse: collapse;">
                    @for($i = 0; $i < 24; $i++)
                        @if($i <= 9)
                            <input type="hidden" value="{{$time = '0'.$i}}">
                        @else
                            <input type="hidden" value="{{$time = $i}}">
                        @endif
                        <tr style="height: 45px;">
                            <td width="15%" align="center" style="border-right: solid white 1px; border-bottom: solid white 1px;"><p class="event_time" style="font-weight: bold; margin: 0;">{{$time}}:00</p></td>
                            <td style="border-bottom: solid white 1px;"><p class="event_content" style="font-weight: bold; margin: 0 10px" align="center"> - </p></td>
                        </tr>
                    @endfor
                </table>
            </div>
            <input type="button" value="ADD" class="btn btn-primary btn-lg btn-block" onclick="location.href='{{route('memoEdit')}}?sch_event_date='+document.getElementsByName('sch_event_date')[0].value">
        </div>
        <script>
            $(function (){
                let login_id = '{{$login_id}}';
                let sch_event_date = $('input[name=sch_event_date]').val();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'get',
                    url: '{{ route('getMemos') }}',
                    dataType: 'json',
                    data: {
                        login_id : login_id,
                        sch_event_date : sch_event_date
                    },
                    success: function (data) {
                        for(let i=0; i<data['memos'].length; i++){
                            document.getElementsByClassName('event_content')[data['memos'][i]['event_time']].innerHTML = '<a href="{{route('memoDetail')}}?memo_id='+data['memos'][i]['id']+'">'+data['memos'][i]['title']+'</a>';
                            document.getElementsByClassName('event_content')[data['memos'][i]['event_time']].align = 'left';
                        }
                    },
                    error: function (data) {
                        alert("메모 로드 실패 : "+data['message']);
                    }
                });
            });
        </script>
    @endif
@endsection
