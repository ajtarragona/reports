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

Route::group(['prefix' => 'ajtarragona/reports','middleware' => ['reports-backend','web','auth','language'],'as'=>'tgn-reports.'	], function () {

    Route::post('/preview/{report_name}', 'Ajtarragona\Reports\Controllers\ReportsController@preview')->name('preview');
    Route::get('/{report_name?}', 'Ajtarragona\Reports\Controllers\ReportsController@home')->name('home');

});