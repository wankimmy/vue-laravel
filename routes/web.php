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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('{path}',"HomeController@index")->where('path','([A-z\d-\/_.]+)?')->middleware('auth');


// Route::get('/{vue_capture?}', function () {
//     return view('layouts.master');
// })->where('vue_capture', '[\/\w\.-]*');
