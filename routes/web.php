<?php

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

Route::get('/', function(){
   return view('welcom'); 
});

// ユーザ登録
Route::get('signup', 'Auth\RegsterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth|RegsterController@register')->name('signup.post');

Route::get('/', 'TasksController@index');

Route::resource('tasks', 'TasksController');
