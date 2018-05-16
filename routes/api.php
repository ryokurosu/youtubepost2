<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/post', function(){

  // 現状はわたすデータがないのでエンプティーアレイをわたします
  $data = [];
  Mail::send('emails.welcome', $data, function($message){
    $message->to('knowrop1208.ymatome@gmail.com')
            ->subject('テスト');
  });

});