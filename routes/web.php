<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/{case?}', 'App\Http\Controllers\HomeController@index')->name('home');
Route::post('save','App\Http\Controllers\HomeController@saveForm')->name('saveform');


Route::group(['prefix' => 'lavita'], function () {

    Route::get('/map-health','App\Http\Controllers\HomeController@mapHealth')->name('load_file');
    Route::post('/map-health','App\Http\Controllers\HomeController@loadImport');

    Route::post('/save-mapping','App\Http\Controllers\HomeController@saveMapping')->name('save-mapping');
    
    Route::get('/add-lines/{id?}','App\Http\Controllers\HomeController@addline')->name('addlines');
    Route::get('/get-mapping/{type?}','App\Http\Controllers\HomeController@getMapping')->name('getMapping');
    
    Route::post('/delete-mapping','App\Http\Controllers\HomeController@deleteMapping')->name('deleteMapping');

    Route::post('/updatehoushold','App\Http\Controllers\HomeController@household')->name('household');


    Route::get('/test','App\Http\Controllers\HomeController@test')->name('test');


    
});
