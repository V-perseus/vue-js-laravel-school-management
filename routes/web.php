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


Route::get('/logout','UserController@logout');
Route::get('/','UserController@index');
Route::post('file-import','AppTemplateController@userImport')->name('file-import');
Route::get('file-export','AppTemplateController@userExport')->name('file-export');
// Route::get('{slug}','UserController@index');
Route::get('{path}', 'UserController@index')->where('path', '(.*)');