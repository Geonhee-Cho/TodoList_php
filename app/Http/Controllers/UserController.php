<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Memo;
use App\User;
use Facade\Ignition\Support\Packagist\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PhpParser\Node\Expr\Cast\Object_;

class UserController extends Controller
{
    public function _construct() {
        \Illuminate\Support\Facades\View::share('test', $this->test() );
    }
    public function userCreate(Request $request){
        return view('user/signup');
    }

    public function test() {
        return true;
    }

    public function userStore(Request $request){
        $validatedData = $request->validate([
            'user_id' => ['bail', 'required', 'min:5', 'max:20'],
            'password' => ['bail', 'required', 'min:8', 'max:20'],
            'confirm_password' => ['bail', 'required', 'min:8', 'max:20'],
            'name' => ['bail', 'required', 'min:2', 'max:10'],
        ]);

        $chkId = User::where('user_id',$request->input('user_id'))
            ->get();


        if(count($chkId) > 0){
            return [
                "statusCode"=>1000,
                "message"=>["user_id" => "이미 존재하는 ID입니다."]
            ];
        }else if($request -> input('password') !== $request -> input('confirm_password')){
            return [
                "statusCode"=>1000,
                "message"=>["confirm_password" => "비밀번호 값이 일치하지 않습니다."]
            ];
        }

        $user = new User();

        $user->user_id = $request->input('user_id');
        $user->password = encrypt($request->input('password'));
        $user->name = $request->input('name');

        $user -> save();

        return ["statusCode"=>200];
    }

    public function userSignin(Request $request){
        return view('user/signin');
    }

    public function userSigninCheck(Request $request){
        $validatedData = $request->validate([
            'user_id' => ['bail', 'required', 'min:5', 'max:20'],
            'password' => ['bail', 'required', 'min:8', 'max:20'],
        ]);

        $user = new User();

        $user->user_id = $request->input('user_id');
        $user->password = $request->input('password');

        $chkId = User::where('user_id',$request->input('user_id'))
            ->get();

        Log::info($chkId);

        try {
            if(count($chkId) == 0){
                return [
                    "statusCode"=>1000,
                    "message"=>["user_id" => "존재하지 않는 ID 입니다."]
                ];
            }

            $userPassword = decrypt($chkId[0]["password"]);

            if($request -> input('password') !== $userPassword){
                return [
                    "statusCode"=>1000,
                    "message"=>["password" => "비밀번호가 일치하지 않습니다."]
                ];
            }

            $request->session()->put('id',$chkId[0]["id"]);

            return [
                "statusCode" => 200,
                "user_name"=>$chkId[0]["name"]
            ];
        } catch (DecryptException $e) {
            return [
                "statusCode" => 1000,
                "message"=>["password" => "비밀번호 복호화에 실패했습니다."]
            ];
        }

    }

    public function userSignOut(Request $request){
        if(!($this->loginCheck($request))){
            return [
                "statusCode" => 1000,
                "message" => "로그인 정보가 없습니다."
            ];
        }
        $request->session()->flush();

        return [
            "statusCode" => 200,
        ];
    }

    public function mypage(Request $request){
        $user = User::find($request->session()->get('id'));
        $memoCount = Memo::where('user_id_no', $request->session()->get('id'))->count();

        return view('user/mypage', ['userInfo' => $user, 'memoCount' => $memoCount]);
    }

    public function drop(Request $request){
        $user = User::find($request->session()->get('id'));

        $user -> delete();
        $request->session()->flush();

        return json_encode(array("statusCode"=>200));
    }

    function loginCheck(Request $request){
        $login_id = $request->session()->get('id');

        if(isset($login_id)){
            return true;
        }else{
            return false;
        }
    }
}
