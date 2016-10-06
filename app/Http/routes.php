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

/******* promote ************/
Route::get('admin/promote', 'Admin\PromoteController@index');
Route::get('admin/promote/app/{id}', 'Admin\PromoteController@getApp');
Route::post('admin/promote/app', 'Admin\PromoteController@postApp');

$menu = Menu::make('MyNavBar', function($menu) {
    $menu->add('Home', 'admin')->attr(array('pre_icon'=>'user'));
    $menu->add('Profile', 'admin/profile')->attr(array('pre_icon'=>'envelope'));
    /** youtube videos **/
    $menu->add('Video')->attr(array('pre_icon'=>'youtube'))->active('admin/playlist/*');
    foreach (\App\library\Menus::getCats() as $cat) {
        $menu->video->add($cat->title, 'admin/playlists/' . $cat->id);
    }    
    $menu->video->add('Create Cat', 'admin/ycat/add')->append('<span class="label label-primary pull-right">NEW</span>');
    /** promote **/
    $menu->add('Promote', 'admin/promote')->attr(array('pre_icon'=>'puzzle-piece'))->active('admin/promote/*');
    
    $menu->add('Layouts', 'Layouts')->attr(array('pre_icon'=>'flask'));
    $menu->add('Graphs', 'graphs')->attr(array('pre_icon'=>'bar-chart-o'));
    $menu->graphs->add('Flot Charts', 'flotcharts');
    $menu->graphs->add('Morris.js Charts', 'morrischarts');
    $menu->graphs->add('Rickshaw Charts', 'rickshawcharts');
    $menu->graphs->add('Chart.js', 'chartjs');
    $menu->graphs->add('Chartist', 'chartist');
    $menu->graphs->add('c3 charts', 'c3charts');
    $menu->graphs->add('Peity Charts', 'peitycharts');
    $menu->graphs->add('Sparkline Charts', 'sparklinecharts');
});

/**
 * api
 */
/*********** videos ***************/
Route::get("api/videos",'Api\ApiController@index');
Route::get("api/playlists",'Api\ApiController@getPlaylists');
Route::get("api/playlists/{catid}",'Api\ApiController@getPlaylists');
Route::get("api/videos/{id}",'Api\ApiController@getVideos');

