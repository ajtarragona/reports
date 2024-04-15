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

Route::group(['prefix' => 'ajtarragona/reports','middleware' => ['web','language'],'as'=>'tgn-reports.'	], function () {
    Route::get('/login', 'Ajtarragona\Reports\Controllers\ReportsController@login')->name('login');
    Route::post('/login', 'Ajtarragona\Reports\Controllers\ReportsController@dologin')->name('dologin');
    Route::get('/logout', 'Ajtarragona\Reports\Controllers\ReportsController@logout')->name('logout');
    Route::get('/thumbnail/{report_name}.jpg', 'Ajtarragona\Reports\Controllers\ReportsController@thumbnail')->name('thumbnail');
});
Route::group(['prefix' => 'ajtarragona/reports','middleware' => ['web','language','reports-backend'],'as'=>'tgn-reports.'	], function () {

    Route::post('/preview/{report_name}', 'Ajtarragona\Reports\Controllers\ReportsController@preview')->name('preview');
    Route::post('/thumbnail/{report_name}', 'Ajtarragona\Reports\Controllers\ReportsController@generateThumbnail')->name('generateThumbnail');
    Route::get('/{report_name?}', 'Ajtarragona\Reports\Controllers\ReportsController@home')->name('home');
    Route::post('/export/{report_name}', 'Ajtarragona\Reports\Controllers\ReportsController@export')->name('export');

});