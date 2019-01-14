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

// Authentication routes.
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Information sheets display routes.
Route::get('information-sheets/{informationSheet}', 'InformationSheetController@render');
Route::get('information-sheets/{informationSheet}/data', 'InformationSheetController@data');

// VueJS
Route::any('{all}', function () {
    return view('app');
})
->middleware('auth')
->where(['all' => '^(?!api/).*']);
