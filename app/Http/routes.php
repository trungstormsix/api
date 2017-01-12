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
Route::group(['namespace' => 'Admin'], function () {
    /**
     * youtube
     */
    Route::get('admin', 'AdminController@index');
    Route::group(['prefix' => 'admin/youtube'], function () {
        Route::get('/cat/add', 'YoutubeController@getYcat');
        Route::get('/cat/edit/{id}', 'YoutubeController@getYcat');
        Route::post('/cat/add', 'YoutubeController@postYcat');
        Route::get('/playlists/{catid}', 'YoutubeController@getPlaylists');
        Route::get('/videos/{id}', 'YoutubeController@videos');
        Route::get('/playlist/add', 'YoutubeController@getPlaylist');
        Route::get('/playlist/edit/{id}', 'YoutubeController@getPlaylist');
        Route::post('/playlist/add', 'YoutubeController@postPlaylist');
        Route::get('/video/add', 'YoutubeController@video');
        Route::get('/video/edit/{id}', 'YoutubeController@video');
        Route::post('/video/save', 'YoutubeController@saveVideo');
        //ajax
        Route::post('/delete', 'YoutubeController@deleteVideo');
        Route::post('/change-playlist', 'YoutubeController@changePlaylist');
    });


    /*     * ***** promote *********** */
    Route::get('admin/promote', 'PromoteController@index');
    Route::get('admin/promote/app/add', 'PromoteController@getApp');
    Route::get('admin/promote/app/{id}', 'PromoteController@getApp');
    Route::post('admin/promote/app', 'PromoteController@postApp');

    /**     * **** idioms *********** */
    Route::get('admin/idioms', 'IdiomController@index');
    Route::get('admin/idioms/search', 'IdiomController@search');
    Route::get('admin/idioms/export', 'IdiomController@export');
    Route::get('admin/idioms/add-cat', 'IdiomController@getCat');
    Route::get('admin/idioms/edit-cat/{id}', 'IdiomController@getCat');
    Route::post('admin/idioms/add-cat', 'IdiomController@postCat');
    Route::get('admin/idioms/{cat_id}', 'IdiomController@idioms');
    Route::get('admin/idioms/idiom/{id}', 'IdiomController@getIdiom');

    /*     * ***************** pic voc *************************** */
    Route::get('picvoc/add', 'PicvocController@add');
    Route::get('picvoc/search-cat', 'PicvocController@searchCat');
    Route::get('picvoc/delete', 'PicvocController@delete');
    /*     * *** ielts ******* */
    Route::group(['prefix' => 'admin/ielts', "namespace" => "IELTS"], function () {
        Route::get('', 'IELTSController@index');
        Route::get('vocabulary', 'IELTSController@vocabulary');
        Route::get('edit-cat/{id}', 'IELTSController@editCat');
        Route::get('add-cat', 'IELTSController@editCat');
        Route::post('add-cat', 'IELTSController@postCat');
        Route::get('cat/{id}', 'IELTSController@listAll');
        Route::get('article/{id}', 'IELTSController@editArticle');
        Route::post('article/save', 'IELTSController@postArticle');
    });
    
     //categories
    Route::group(['prefix' => 'admin/categories'], function (){
        Route::get('', 'CategoriesController@index');
        Route::get('/create', 'CategoriesController@create');
        Route::post('/save', 'CategoriesController@update');
        Route::get('/edit/{id}', 'CategoriesController@edit');
        Route::get('/delete/{id}', 'CategoriesController@delete');
    });
    //Articles
    Route::group(['prefix' => 'admin/articles'], function (){
    Route::get('', 'ArticlesController@index');
    Route::get('/create', 'ArticlesController@create');
    Route::post('/save', 'ArticlesController@update');
    Route::get('/edit/{id}', 'ArticlesController@edit');
    Route::get('/delete/{id}', 'ArticlesController@delete');
    });
});

Route::get('crawl/videos', 'Crawl\YoutubeController@index');
/* * ***** idioms *********** */
Route::get('admin/english-test/get-idiom-test', 'Crawl\EnglishTestController@index');
Route::get('admin/idioms/get-idiom-example', 'Crawl\IdiomController@getExample');
Route::get('admin/idioms/get-top-50', 'Crawl\IdiomController@getTop50Idioms');
Route::get('admin/idioms/crawl/idiom', 'Crawl\IdiomController@getIdioms');
Route::get('admin/idioms/crawl/english-club-phrasal-verb', 'Crawl\IdiomController@getPhrasalVerbs');

/* * ***************** pic voc *************************** */
Route::get('admin/picvoc/crawl', 'Crawl\PicvocController@getCommonWords');
Route::get('admin/picvoc/get-oxford-words', 'Crawl\PicvocController@getOxfordMean');



//ajax
Route::get('admin/idiom/ajax-change-word', 'Admin\IdiomController@ajaxChangWord');



/* * *********** listening **************** */
Route::group(['prefix' => 'admin/listening'], function () {

    Route::get('', 'Admin\ListeningController@index');
    Route::get('cat/{id}', 'Admin\ListeningController@dialogs');
    Route::get('dialog/{id}', 'Admin\ListeningController@getDialog');
    Route::post('dialog/save', 'Admin\ListeningController@postDialog');

    Route::get('remove-cat', 'Admin\ListeningController@removeCat');
    Route::get('add-cat', 'Admin\ListeningController@ajaxAddCat');
    Route::get('autocomplete-cat', 'Admin\ListeningController@ajaxGetCats');

    Route::get('ajax-remove-grammar', 'Admin\ListeningController@ajaxremoveGrammar');
    Route::get('ajax-add-grammar', 'Admin\ListeningController@ajaxAddGrammar');
    Route::get('autocomplete-grammar', 'Admin\ListeningController@ajaxGetGrammars');
    Route::get('ajax-ordering', 'Admin\ListeningController@ajaxUpdateOrder');

    Route::get('reports', 'Admin\ListeningController@reports');
    Route::get('report/fix', 'Admin\ListeningController@ajaxFixReport');
});
Route::get('crawl/listening', 'Crawl\ListeningController@index');

/* * *********** user **************** */
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

Route::get('funny/get-images', 'Crawl\FunnyImageController@index');
Route::get('funny/get-9gag-images', 'Crawl\FunnyImageController@get9Gag');

Route::group(['namespace' => 'Crawl', 'prefix' => 'crawl'], function () {
    Route::get('test/get-toeic', 'Test\TestController@index');
});

/**
 * create menu
 */
$menu = Menu::make('MyNavBar', function($menu) {
            $menu->add('Home', 'admin')->attr(array('pre_icon' => 'user'));
            /** youtube videos * */
            $menu->add('Video')->attr(array('pre_icon' => 'youtube'))->active('admin/youtube/*');
            foreach (\App\library\Menus::getCats() as $cat) {
                $menu->video->add($cat->title, 'admin/youtube/playlists/' . $cat->id);
            }
            $menu->video->add('Create Cat', 'admin/youtube/cat/add')->append('<span class="label label-primary pull-right">NEW</span>')->nickname("createCat");
            $menu->item("createCat")->divide(array('class' => 'my-divider'));


            $menu->video->add('Crawl Daily Videos', 'crawl/videos')->append('<span class="label label-primary pull-right">crawl</span>');
            $menu->video->add('Get 5 Funny Images', 'funny/get-images')->attr(array('pre_icon' => 'download'));


            /** promote * */
            $menu->add('Promote', 'admin/promote')->attr(array('pre_icon' => 'puzzle-piece'))->active('admin/promote/*');

            $menu->add('Get Test', 'admin/english-test/get-idiom-test')->attr(array('pre_icon' => 'check'));
            //idioms
            $menu->add('Idioms', 'idioms')->attr(array('pre_icon' => 'info'))->active('admin/idioms/*');
            $menu->idioms->add('Cat', 'admin/idioms')->attr(array('pre_icon' => 'info'))->active('admin/idioms/*');
            $menu->idioms->add('Get Idiom Ex', 'admin/idioms/get-idiom-example')->attr(array('pre_icon' => 'check'));
            $menu->idioms->add('Export', 'admin/idioms/export')->attr(array('pre_icon' => 'folder'));

            //idioms
            $menu->add('Listening', 'listening')->attr(array('pre_icon' => 'phone'))->active('admin/listening/*');
            $menu->listening->add('Cat', 'admin/listening')->attr(array('pre_icon' => 'phone'))->active('admin/listening/*');
            $menu->listening->add('Reports', 'admin/listening/reports')->attr(array('pre_icon' => 'report'))->active('admin/listening/reports/*');

            //users
            $menu->add('Users Manager', 'users')->attr(array('pre_icon' => 'user'));
            $menu->usersManager->add('Users', 'admin/users')->attr(array('pre_icon' => 'user'))->active('admin/users/*');
            $menu->usersManager->add('Permissions', 'admin/user/permissions')->attr(array('pre_icon' => 'user'))->active('admin/user/permission/*');
            $menu->usersManager->add('Roles', 'admin/user/roles')->attr(array('pre_icon' => 'users'))->active('admin/user/role/*');
            $menu->usersManager->add('Profile', 'admin/user/profile')->attr(array('pre_icon' => 'envelope'));

            $menu->add('Pic Voc', 'admin/picvoc/get-oxford-words')->attr(array('pre_icon' => 'user'));



            $menu->add('IELTS', 'admin/ielts')->attr(array('pre_icon' => 'bar-chart-o'))->active('admin/ielts/*');
            $menu->iELTS->add('Types', 'admin/ielts')->attr(array('pre_icon' => 'user'));
            $menu->iELTS->add('Vocabulary', 'admin/ielts/vocabulary')->attr(array('pre_icon' => 'user'));
            foreach (App\Models\IELTS\IELTSCat::where("type", "article")->get() as $cat) {
                $menu->iELTS->add($cat->title, 'admin/ielts/cat/' . $cat->id);
            }
            $menu->add('Content', '')->attr(array('pre_icon' => 'file-text'))->active('admin/categories/*|admin/articles/*');
            $menu->content->add('Categories', 'admin/categories')->attr(array('pre_icon' => 'tag'));

            $menu->content->add('Articles', 'admin/articles')->attr(array('pre_icon' => 'file-text'))->active('admin/listening/*');;
        });

/**
 * api
 */
Route::group(['namespace' => 'Api', 'prefix' => 'api'], function () {

    /*     * ********* videos ************** */
    Route::get("videos", 'ApiController@index');
//    Route::get("playlists", 'ApiController@getPlaylists');
    Route::get("playlists/{catid}", 'ApiController@getPlaylists');
    Route::get("videos/{id}", 'ApiController@getVideos');

    Route::post("auth/login", 'ApiLoginController@login');
    Route::post("auth/create-user", 'ApiLoginController@createUser');

    /**
     * listening
     */
    Route::get("listening/dialogs", 'ListeningController@index');


    /*     * *
     * picvoc
     */
    Route::get('picvoc/cats', 'PicvocController@cats');
});
Route::get("playlists", 'ApiController@getPlaylists');
Route::get("playlists/{catid}", 'ApiController@getPlaylists');
Route::get('funny/images', 'Api\ImagesController@images');
Route::post('funny/like', 'Api\ImagesController@like');
/**
 * Front end
 */
Route::get('/', 'HomeController@index');
Route::get('/grammar', 'Front\GrammarTestController@index');
Route::get('/grammar/test/{id}', 'Front\GrammarTestController@tests');
Route::post('/grammar/test/{id}', 'Front\GrammarTestController@postTests');
 
Route::Get('listening', 'Front\ListeningFrontController@index');
Route::Get('listening/dialogs/{id}', 'Front\ListeningFrontController@dialogs');
Route::Get('listening/test/{id}', 'Front\ListeningFrontController@test');

//Route::get('api/users/{user}', function (App\User $user) {
//    return $user->email;
//});
/*
    Login facebook
 */
Route::get('auth/facebook', 'Auth\AuthController@redirectToProvider');
Route::get('auth/facebook/callback', 'Auth\AuthController@handleProviderCallback');
/*
    Build HTML contact form
 */
Route::get('buildform',function(){
    return view('buildform');
});
