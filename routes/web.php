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
Route::get('/report/{name}/{year}', 'api\v1\ReportController@download');

Route::get('/{catchall?}', function () {
    return view('welcome');
})->where('catchall', '^(?!api).*$')->name('administration');


