<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','MainController@index')->name('index');

// api
Route::prefix('')->group(function () {
    Route::prefix('user')->group(function () {
        Route::post('',[UserController::class, 'userStore'])->name('userStore');
        Route::delete('',[UserController::class, 'drop'])->name('drop');
        Route::post('/login',[UserController::class, 'userSigninCheck'])->name('userSigninCheck');
        Route::delete('/logout',[UserController::class, 'userSignOut'])->name('userSignOut');
    });

    Route::middleware('customAuth')->group(function () {
        Route::prefix('memos')->group(function (){
            Route::get('','MemoController@getMemos')->name('getMemos');
            Route::post('/memo','MemoController@postMemo')->name('postMemo');
            Route::get('/memo','MemoController@getMemo')->name('getMemo');
            Route::delete('/memo','MemoController@deleteMemo')->name('deleteMemo');
            Route::patch('/memo','MemoController@patchMemo')->name('patchMemo');
        });
    });
});

// page
Route::prefix('')->group(function () {
    Route::prefix('user')->group(function () {
        Route::middleware('customAuth2')->group(function () {
            Route::get('/signup-page', [UserController::class, 'userCreate'])->name('userCreate');
            Route::get('/signin-page', [UserController::class, 'userSignin'])->name('userSignin');
        });
        Route::middleware('customAuth')->group(function () {
            Route::get('/mypage', [UserController::class, 'myPage'])->name('myPage');
        });
    });

    Route::middleware('customAuth')->group(function () {
        Route::prefix('memos')->group(function (){
            Route::get('/memo-list','MemoController@memoList')->name('memoList');
            Route::get('/memo-edit','MemoController@memoEdit')->name('memoEdit');
            Route::get('/memo-detail','MemoController@memoDetail')->name('memoDetail');
        });
    });
});
