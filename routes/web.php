<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;



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
Route::get('/', function () {
    return view('welcome');
});

Route::get('/check', function(){
   //$user = Http::get('http://127.0.0.1:8000/api/user-details');
   $request = Request::create('/api/users', 'get');
  // $response = Route::dispatch($request);
   $response = app()->handle($request);
   $jsonBody = json_decode($response->getContent(), true);
   $result = $jsonBody['data'];
   return view('hello', compact('result'));
});
