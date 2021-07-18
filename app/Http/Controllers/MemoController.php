<?php

namespace App\Http\Controllers;

use App\Memo;
use Carbon\Carbon;
use Facade\Ignition\Support\Packagist\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MemoController extends Controller
{
    public function memoList(Request $request){
        $sch_event_date = $request->sch_event_date;

        if($sch_event_date == null){
            $sch_event_date = date('Y-m-d');
        }

        return view('memo/memoList',['sch_event_date' => $sch_event_date]);
    }

    public function memoEdit(Request $request){
        $memo = new Memo();
        if(isset($request -> memo_id)){
            $memo = $this->getMemo($request->memo_id);
            return view('memo/memoEdit',['memo' => $memo]);
        }
        $memo -> event_date = $request -> sch_event_date;

        return view('memo/memoEdit',['memo' => $memo]);
    }

    public function memoDetail(Request $request){
        $memo = $this->getMemo($request->memo_id);

        return view('memo/memoDetail',['memo' => $memo]);
    }

    public function getMemos(Request $request){
        $memos = Memo::where([
            ['user_id_no', '=', $request->session()->get('id')],
            ['event_date', '=', $request->input('sch_event_date')],
        ])->orderBy('event_time','asc')->get();

        return [
            "statusCode"=>200,
            "memos"=>$memos
        ];
    }

    public function postMemo(Request $request){
        $validatedData = $request->validate([
            'event_date' => ['required'],
            'st_event_time' => ['required'],
            'end_event_time' => ['required'],
            'title' => ['required'],
            'content' => ['required'],
        ]);

        $event_date =$request->input('event_date');
        $st_event_time =$request->input('st_event_time');
        $end_event_time =$request->input('end_event_time');
        $title =$request->input('title');
        $content =$request->input('content');

        if($st_event_time > $end_event_time){
            return [
                "statusCode"=>1000,
                "message"=>["st_event_time" => "시간을 확인해주세요."
                ]];
        }

        $memo = Memo::where([
            ["user_id_no","=",$request->session()->get('id')],
            ["event_date", "=", $event_date]
        ])->whereBetween('event_time', [$st_event_time, $end_event_time])
            ->get();

        Log::info($memo);

        if(count($memo) > 0){
            return [
                "statusCode"=>1000,
                "message"=>["st_event_time" => "해당 시간대에 메모가 존재합니다."
                ]];
        }

        $insertMemoList = [];
        $now = Carbon::now()->toDateString();
        for($i = $st_event_time; $i <= $end_event_time; $i++){
            array_push($insertMemoList, [
                'user_id_no' => $request->session()->get('id'),
                'event_date' => $event_date,
                'event_time' => $i,
                'title' => $title,
                'content' => $content,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        Memo::insert($insertMemoList);

        return ["statusCode"=>200];
    }

    public function getMemo($memo_id){
        $memo = Memo::find($memo_id);

        return $memo;
    }

    public function deleteMemo(Request $request){
        $memo = Memo::find($request -> memo_id);

        $memo->delete();

        return ["statusCode"=>200];
    }

    public function patchMemo(Request $request){
        $validatedData = $request->validate([
            'title' => ['required'],
            'content' => ['required'],
        ]);

        $id =$request->input('id');
        $title =$request->input('title');
        $content =$request->input('content');

        $memo = $this->getMemo($id);

        $memo -> title = $title;
        $memo -> content = $content;

        $memo -> save();

        return ["statusCode"=>200];
    }
}
