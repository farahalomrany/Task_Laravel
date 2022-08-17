<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;

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
Route::group(['middleware' => ['api'],'namespace'=>'Api'],function(){
      Route::group(['prefix' => 'user','namespace'=>'User' ],function (){
        Route::post('login', 'UserAuthController@login');//login api

        Route::post('logout','UserAuthController@logout') -> middleware(['auth.guard:user-api']);//logout api

      });    
});
Route:: post('/user/store', 'Api\User\UserAuthController@store');//store user api

Route:: post('/order/store', 'OrderController@store');//order items api if user will authenticated

//print order and order details if user will authenticated 
Route:: post('/order/print', 'OrderController@print_order_and_orderdetails');

Route:: post('/user/replay', 'ReplayController@replay');//replay order api if user will authenticated
