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

/******* promote ************/
Route::get('admin/promote', 'Admin\PromoteController@index');
Route::get('admin/promote/app/{id}', 'Admin\PromoteController@getApp');
Route::post('admin/promote/app', 'Admin\PromoteController@postApp');
/******* idioms ************/
Route::get('admin/english-test/get-idiom-test', 'Crawl\EnglishTestController@index');
Route::get('admin/idioms', 'Admin\IdiomController@index');
Route::get('admin/idioms/search', 'Admin\IdiomController@search');
Route::get('admin/idioms/get-idiom-example', 'Crawl\IdiomController@getExample');
Route::get('admin/idioms/get-top-50', 'Crawl\IdiomController@getTop50Idioms');
Route::get('admin/idioms/export', 'Admin\IdiomController@export');
//ajax
Route::get('admin/idiom/ajax-change-word', 'Admin\IdiomController@ajaxChangWord');


Route::get('admin/idioms/add-cat', 'Admin\IdiomController@getCat');
Route::get('admin/idioms/edit-cat/{id}', 'Admin\IdiomController@getCat');
Route::post('admin/idioms/add-cat', 'Admin\IdiomController@postCat');

Route::get('admin/idioms/{cat_id}', 'Admin\IdiomController@idioms');
Route::get('admin/idioms/idiom/{id}', 'Admin\IdiomController@getIdiom');
/************* listening *****************/
Route::get('admin/listening', 'Admin\ListeningController@index');
Route::get('admin/listening/cat/{id}', 'Admin\ListeningController@dialogs');
Route::get('admin/listening/dialog/{id}', 'Admin\ListeningController@getDialog');
Route::post('admin/listening/dialog/save', 'Admin\ListeningController@postDialog');

Route::get('admin/listening/remove-cat', 'Admin\ListeningController@removeCat');
Route::get('admin/listening/add-cat', 'Admin\ListeningController@ajaxAddCat');
Route::get('admin/listening/autocomplete-cat', 'Admin\ListeningController@ajaxGetCats');

Route::get('admin/listening/ajax-remove-grammar', 'Admin\ListeningController@ajaxremoveGrammar');
Route::get('admin/listening/ajax-add-grammar', 'Admin\ListeningController@ajaxAddGrammar');
Route::get('admin/listening/autocomplete-grammar', 'Admin\ListeningController@ajaxGetGrammars');

/************* user *****************/
Route::get('admin/users', 'Admin\UserController@index');
Route::get('admin/users/edit/{id}', 'Admin\UserController@edit');
Route::post('admin/user/save', 'Admin\UserController@update'); 

Route::get('admin/user/permissions', 'Admin\PermissionController@index');
Route::get('admin/user/permission/edit/{id}', 'Admin\PermissionController@edit');
Route::get('admin/user/permission/delete/{id}', 'Admin\PermissionController@delete');
Route::get('admin/user/permission/create', 'Admin\PermissionController@create');
Route::patch('admin/user/permission/update/{id}', 'Admin\PermissionController@update');
Route::post('admin/user/permission/save', 'Admin\PermissionController@store');

Route::get('admin/user/roles', 'Admin\RoleController@index');
Route::get('admin/user/role/edit/{id}', 'Admin\RoleController@edit');
Route::get('admin/user/role/delete/{id}', 'Admin\RoleController@delete');
Route::get('admin/user/role/create', 'Admin\RoleController@create');
Route::patch('admin/user/role/update/{id}', 'Admin\RoleController@update');
Route::post('admin/user/role/save', 'Admin\RoleController@store'); 
//profile
Route::get('admin/user/profile', 'Admin\UserController@getProfile');
Route::post('admin/user/profile', 'Admin\UserController@postProfile');


//crawl Truyen
Route::get('admin/truyen/crawl', 'Crawl\TruyenController@index');
Route::get('admin/story/sp', 'Crawl\SpanishAudioBookController@index');


/**
 * create menu
 */
$menu = Menu::make('MyNavBar', function($menu) {
    $menu->add('Home', 'admin')->attr(array('pre_icon'=>'user'));
    /** youtube videos **/
    $menu->add('Video')->attr(array('pre_icon'=>'youtube'))->active('admin/playlist/*');
    foreach (\App\library\Menus::getCats() as $cat) {
        $menu->video->add($cat->title, 'admin/playlists/' . $cat->id);
    }    
    $menu->video->add('Create Cat', 'admin/ycat/add')->append('<span class="label label-primary pull-right">NEW</span>');
    /** promote **/
    $menu->add('Promote', 'admin/promote')->attr(array('pre_icon'=>'puzzle-piece'))->active('admin/promote/*');
    
    $menu->add('Get Test', 'admin/english-test/get-idiom-test')->attr(array('pre_icon'=>'check'));
    //idioms
    $menu->add('Idioms', 'idioms')->attr(array('pre_icon'=>'info'))->active('admin/idioms/*');
    $menu->idioms->add('Cat', 'admin/idioms')->attr(array('pre_icon'=>'info'))->active('admin/idioms/*');
    $menu->idioms->add('Get Idiom Ex', 'admin/idioms/get-idiom-example')->attr(array('pre_icon'=>'check'));
    $menu->idioms->add('Export', 'admin/idioms/export')->attr(array('pre_icon'=>'folder'));
    
     //idioms
    $menu->add('Listening', 'listening')->attr(array('pre_icon'=>'phone'))->active('admin/listening/*');
    $menu->listening->add('Cat', 'admin/listening')->attr(array('pre_icon'=>'phone'))->active('admin/listening/*');
    
    //users
    $menu->add('Users Manager', 'users')->attr(array('pre_icon'=>'user'));
    $menu->usersManager->add('Users', 'admin/users')->attr(array('pre_icon'=>'user'))->active('admin/users/*');
    $menu->usersManager->add('Permissions', 'admin/user/permissions')->attr(array('pre_icon'=>'user'))->active('admin/user/permission/*');
    $menu->usersManager->add('Roles', 'admin/user/roles')->attr(array('pre_icon'=>'users'))->active('admin/user/role/*');
    $menu->usersManager->add('Profile', 'admin/user/profile')->attr(array('pre_icon'=>'envelope'));

    
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

/**
 * listening
 */
Route::get("api/listening/dialogs",'Api\ListeningController@index');


/**
 * Front end
 */
Route::get('/', 'HomeController@index');
Route::get('/grammar', 'Front\GrammarTestController@index');
Route::get('/grammar/test/{id}', 'Front\GrammarTestController@tests');
Route::post('/grammar/test/{id}', 'Front\GrammarTestController@postTests');
