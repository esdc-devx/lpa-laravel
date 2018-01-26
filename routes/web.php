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

Route::get('/', function () {
    return view('app');
})->middleware('auth');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Auth::routes();

//Route::resource('projects', 'ProjectController', ['only' => 'index']);
Route::resource('projects', 'ProjectController');

// Vue
Route::any('{all}', function () {
    return view('app');
})
->middleware('auth')
->where(['all' => '.*']);
