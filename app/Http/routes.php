<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
//
Route::auth();

Route::get('/', 'HomeController@index');

Route::get('profile', [
    'middleware' => 'auth',
    'uses' => 'HomeController@show'
]);


/**
 * admin
 */
Route::get('admin', 'Admin\AdminController@index');
Route::get('admin/ycat/add', 'Admin\AdminController@getYcat');
Route::get('admin/ycat/edit/{id}', 'Admin\AdminController@getYcat');
Route::post('admin/ycat/add', 'Admin\AdminController@postYcat');

Route::get('admin/playlists/{catid}', 'Admin\AdminController@getPlaylists');
Route::get('admin/videos/{id}', 'Admin\AdminController@videos');
Route::get('admin/playlist/add', 'Admin\AdminController@getPlaylist');
Route::get('admin/playlist/edit/{id}', 'Admin\AdminController@getPlaylist');
Route::post('admin/playlist/add', 'Admin\AdminController@postPlaylist');

/************* user *****************/
Route::get('admin/profile', 'Admin\AdminController@getProfile');
Route::post('admin/profile', 'Admin\AdminController@postProfile');


/**
 * api
 */
/*********** videos ***************/
Route::get("api/videos",'Api\ApiController@index');
Route::get("api/playlists",'Api\ApiController@getPlaylists');
Route::get("api/playlists/{catid}",'Api\ApiController@getPlaylists');
Route::get("api/videos/{id}",'Api\ApiController@getVideos');