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

/*Route::get('/', function () {
    return view('index');
});*/

Route::get('/create-new','App\Http\Controllers\StoriesController@createNewStories');
Route::get('/create-top','App\Http\Controllers\StoriesController@createTopStories');
Route::get('/new','App\Http\Controllers\StoriesController@showNewStories');
Route::get('/best','App\Http\Controllers\StoriesController@showBestStories');
Route::get('/top','App\Http\Controllers\StoriesController@showTopStories');
//Route::get('/','App\Http\Controllers\StoriesController@displayAllStories');


Route::get('/create-comments','App\Http\Controllers\CommentsController@storeComments');
Route::get('/details/{id}','App\Http\Controllers\CommentsController@showComments');
