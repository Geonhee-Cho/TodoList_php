@extends('layout')
@section('content')
    <?php
    $login_id = session()->get('id');
    ?>
    메인페이지
    @if(isset($login_id))
        <a href="{{route('memoList')}}">메모장</a>
    @endif
@endsection
