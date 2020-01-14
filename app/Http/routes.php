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

        Route::get('/en-cats', 'YoutubeController@getEnCats');
        Route::get('/cat/en-add', 'YoutubeController@getYcatEn');
        Route::get('/cat/en-edit/{id}', 'YoutubeController@getYcatEn');
        Route::post('/en-cat/add', 'YoutubeController@postYcatEn');
        Route::get('/en-playlists/{catid}', 'YoutubeController@getEnPlaylists');
        
        Route::get('/playlists/{catid}', 'YoutubeController@getPlaylists');
        Route::get('/search-playlists', 'YoutubeController@searchPlaylists');
        Route::get('/videos/{id}', 'YoutubeController@videos');
        Route::get('/search-videos', 'YoutubeController@searchVideos');

        Route::get('/playlist/add', 'YoutubeController@getPlaylist');
        Route::get('/playlist/edit/{id}', 'YoutubeController@getPlaylist');
        Route::post('/playlist/add', 'YoutubeController@postPlaylist');
        Route::get('/video/add', 'YoutubeController@video');
        Route::get('/video/edit/{id}', 'YoutubeController@video');
        Route::post('/video/save', 'YoutubeController@saveVideo');
        
        Route::get('/playlist/crawl-videos/{id}', 'YoutubeController@crawlVideos');
        Route::get('/video/crawl-a3-sub', 'YoutubeController@crawlAmazoneSub');
        Route::get('/video/crawl-y-sub', 'YoutubeController@crawlYoutubeSub');
        //ajax
        Route::post('/delete', 'YoutubeController@deleteVideo');
        Route::post('/change-playlist', 'YoutubeController@changePlaylist');
    });


    /*     * ***** promote *********** */
    Route::get('admin/promote', 'PromoteController@index');
    Route::get('admin/promote/app/add', 'PromoteController@getApp');
    Route::get('admin/promote/app/{id}', 'PromoteController@getApp');
    Route::post('admin/promote/app', 'PromoteController@postApp');
    Route::get('admin/promote/cat/add', 'PromoteController@getCat');
    Route::get('admin/promote/cat/{id}', 'PromoteController@getCat');
    Route::post('admin/promote/cat', 'PromoteController@postCat');

    /**     * **** idioms *********** */
    Route::get('admin/idioms', 'IdiomController@index');
    Route::get('admin/idioms/search', 'IdiomController@search');
    Route::get('admin/idioms/export', 'IdiomController@export');
    Route::get('admin/idioms/add-cat', 'IdiomController@getCat');
    Route::get('admin/idioms/edit-cat/{id}', 'IdiomController@getCat');
    Route::post('admin/idioms/add-cat', 'IdiomController@postCat');
    Route::get('admin/idioms/{cat_id}', 'IdiomController@idioms');
    Route::get('admin/idioms/idiom/{id}', 'IdiomController@getIdiom');
    Route::get('admin/idioms/idiom/create', 'IdiomController@getIdiom');

    /*     * ***************** pic voc *************************** */
    Route::get('picvoc/add', 'PicvocController@add');
    Route::get('picvoc/search-cat', 'PicvocController@searchCat');
        Route::post('picvoc/search-cat', 'PicvocController@searchCat');

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
        Route::get('search', array(
            'as' => 'ielts_articles.search',
            'uses' => 'IELTSController@search'
        ));
        Route::get('voc/crawl/{id}', 'IELTSController@updateWord');

    });

    //categories
    Route::group(['prefix' => 'admin/categories'], function () {
        Route::get('', 'CategoriesController@index');
        Route::get('/create', 'CategoriesController@create');
        Route::get('/{cat_id}', 'CategoriesController@index');
        Route::post('/save', 'CategoriesController@update');
        Route::get('/edit/{id}', 'CategoriesController@edit');
        Route::get('/delete/{id}', 'CategoriesController@delete');
    });
    //Articles
    Route::group(['prefix' => 'admin/articles'], function () {
        Route::get('', 'ArticlesController@index');
        Route::get('article/{cat_id}', array(
            'as' => 'articels.list_cat',
            'uses' => 'ArticlesController@articles'
        ));
        Route::get('/create', 'ArticlesController@create');
        Route::post('/save', 'ArticlesController@update');
        Route::get('/edit/{id}', 'ArticlesController@edit');
        Route::get('/delete/{id}', 'ArticlesController@delete');
        Route::post('article/delete-articles', array(
            'as' => 'articels.deleteArts',
            'uses' => 'ArticlesController@postDeleteArts'
        ));
    });

    //categories
    Route::group(['prefix' => 'admin/tests'], function () {
        Route::get('', array(
            'as' => 'tests.list',
            'uses' => 'TestController@index'
        ));
        Route::get('/{id}', array(
            'as' => 'tests.list',
            'uses' => 'TestController@index'
        ));
        Route::get('/test/{id}', array(
            'as' => 'tests.test',
            'uses' => 'TestController@test'
        ));
        Route::get('/test/trim/{id}', array(
            'as' => 'tests.trim',
            'uses' => 'TestController@trim'
        ));
        Route::post('/test/ajax_save_question', array(
            'as' => 'tests.ajax_save_question',
            'uses' => 'TestController@ajaxSaveQuestion'
        ));
        Route::get('/delete/{id}', array(
            'as' => 'tests.delete',
            'uses' => 'TestController@delete'
        ));

        Route::get('/delete-all-tests/{cat_id}', array(
            'as' => 'tests.delete-all',
            'uses' => 'TestController@deleteAllTests'
        ));

        Route::get('/question/delete/{id}', array(
            'as' => 'question.delete',
            'uses' => 'TestController@deleteQuestion'
        ));
//        Route::get('/create', 'CategoriesController@create');
//        Route::post('/save', 'CategoriesController@update');
//        Route::get('/edit/{id}', 'CategoriesController@edit');
//        Route::get('/delete/{id}', 'CategoriesController@delete');
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
Route::get('admin/picvoc/crawl-quizlet', 'Crawl\PicvocController@getCrawlQuizlet');
Route::post('admin/picvoc/crawl-quizlet', 'Crawl\PicvocController@crawlQuizlet');
Route::get('admin/picvoc/get-oxford-words', 'Crawl\PicvocController@getOxfordMean');
Route::get('admin/picvoc/get-oxford-word/{id}', 'Crawl\PicvocController@getOxfordMeanOfWord');
Route::get('admin/picvoc/delete-voc/{id}', 'Crawl\PicvocController@deleteVoc');
Route::get('admin/picvoc/cats', 'Admin\PicvocController@cats');
Route::get('admin/picvoc/vocabularies/{cat_id}', 'Admin\PicvocController@vocs');
Route::get('admin/picvoc/voc/{voc_id}', 'Admin\PicvocController@voc');
Route::get('admin/picvoc/means', 'Admin\PicvocController@means');



//ajax
Route::get('admin/idiom/ajax-change-word', 'Admin\IdiomController@ajaxChangWord');



/* * *********** listening **************** */
Route::group(['prefix' => 'admin/listening'], function () {

    Route::get('', 'Admin\ListeningController@index');
    Route::get('cat/{id}', 'Admin\ListeningController@dialogs');
    Route::get('dialog/{id}', 'Admin\ListeningController@getDialog');
    Route::post('dialog/save', 'Admin\ListeningController@postDialog');
    Route::get('dialogs/search', array(
        'as' => 'listening.search',
        'uses' => 'Admin\ListeningController@searchDialog'
    ));

    Route::get('remove-cat', 'Admin\ListeningController@removeCat');
    Route::get('add-cat', 'Admin\ListeningController@ajaxAddCat');
    Route::get('autocomplete-cat', 'Admin\ListeningController@ajaxGetCats');

    Route::get('ajax-remove-grammar', 'Admin\ListeningController@ajaxremoveGrammar');
    Route::get('ajax-add-grammar', 'Admin\ListeningController@ajaxAddGrammar');
    Route::get('autocomplete-grammar', 'Admin\ListeningController@ajaxGetGrammars');
    Route::get('ajax-ordering', 'Admin\ListeningController@ajaxUpdateOrder');
    Route::post('ajax-add-qu', 'Admin\ListeningController@postQuestionAjax');

    Route::get('reports', 'Admin\ListeningController@reports');
    Route::get('report/fix', 'Admin\ListeningController@ajaxFixReport');
    Route::get('video', 'Admin\ListeningController@createVideo');
    Route::get('video/{id}', 'Admin\ListeningController@createVideoId');
    Route::get('jpg2png', 'Admin\ListeningController@jpg2Png');
    Route::get('crawl-y-sub', 'Admin\ListeningController@crawlYoutubeSub');
    Route::get('crawl-pl', 'Admin\ListeningController@crawlPlayList');
    
    
});
Route::group(['prefix' => 'admin/story'], function () {
    Route::get('cats', 'Admin\StoryController@cats');
    Route::get('stories/{cat_id}', 'Admin\StoryController@stories');
    Route::get('story/{id}', 'Admin\StoryController@getStory');
    Route::get('story/delete/{id}', 'Admin\StoryController@deleteStory');
    Route::post('story/save', 'Admin\StoryController@postStory');
    Route::get('video/{id}', 'Admin\StoryController@createVideoId'); 
    Route::get('video', 'Admin\StoryController@createVideo'); 
    Route::get('crawl-pl', 'Admin\StoryController@crawlPlayList');
    Route::get('crawl-y-sub', 'Admin\StoryController@crawlYoutubeSub');
    Route::get('duration/{id}', 'Admin\StoryController@setStoryDuration');    
    //ajax
    Route::get('remove-cat', 'Admin\StoryController@removeCat');
    Route::get('add-cat', 'Admin\StoryController@ajaxAddCat');
    Route::get('autocomplete-cat', 'Admin\StoryController@ajaxGetCats');
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
Route::get('admin/story/en', 'Crawl\AudioBookController@index');
Route::get('admin/story/en/cat/{cat_id}', 'Crawl\AudioBookController@crawlDataFromOCoderEducation');

Route::get('funny/get-images', 'Crawl\FunnyImageController@index');
Route::get('funny/get-9gag-images', 'Crawl\FunnyImageController@get9Gag');

Route::group(['namespace' => 'Crawl', 'prefix' => 'crawl'], function () {
    Route::get('test/get-toeic', 'Test\TestController@index');
    Route::get('make-up', 'MakeupController@index');
});

Route::group(['prefix' => 'admin/makeup'], function () {
    Route::get('cat', 'Admin\MakeupController@index');
    Route::get('cat/create', 'Admin\MakeupController@editCat');
    Route::get('cat/edit/{id}', 'Admin\MakeupController@editCat');
    Route::post('cat/update', 'Admin\MakeupController@updateCat');
    Route::get('cat/delete/{id}', 'Admin\MakeupController@deleteCat');

    Route::get('articles', 'Admin\MakeupController@articles');
    Route::get('articles/{cat_id}', 'Admin\MakeupController@articles');
    Route::get('article/create', 'Admin\MakeupController@editArticle');
    Route::get('article/edit/{id}', 'Admin\MakeupController@editArticle');
    Route::post('article/update', 'Admin\MakeupController@updateArticle');
    Route::get('article/delete/{id}', 'Admin\MakeupController@deleteArticle');
    Route::get('article/publish/{id}', 'Admin\MakeupController@publishArticle');
});
Route::group(['prefix' => 'admin/content'], function () {
    Route::get('crawl-jp', 'Crawl\TiengNhatController@index');
    Route::get('crawl-jp-4u', 'Crawl\TiengNhatController@getJp4u');

    Route::get('crawl-jp-kanji-en', 'Crawl\TiengNhatController@crawlKanji');
    Route::get('crawl-jp-voc-en', 'Crawl\TiengNhatController@crawlVocabulary');
    Route::get('crawl-jp-test', 'Crawl\TiengNhatController@getTest');
    Route::get('crawl-jp-test-img-audio', 'Crawl\TiengNhatController@crawlImagesAndAudios');
    Route::get('crawl-jp-kanji-n2-en', 'Crawl\TiengNhatController@getN2KanjiEnglish');

    Route::get('crawl-de', 'Crawl\GermanController@index');
    Route::get('crawl-de/lesson', 'Crawl\GermanController@getLessonByLink');
//        Route::get('crawl-jp-test', 'Crawl\TiengNhatController@getTest');
});

Route::get('admin/dictionary', 'Admin\DictionaryController@index');
Route::get('admin/dictionary/crawl', 'Admin\DictionaryController@crawl');
Route::get('admin/dictionary/delete-for-recrawl', 'Admin\DictionaryController@resetNotGetMean');

Route::get('admin/dictionary/search', 'Admin\DictionaryController@search');
Route::get('admin/dictionary/delete-search', 'Admin\DictionaryController@deleteSearch');
Route::get('admin/dictionary/word/get-delete-mean', 'Admin\DictionaryController@getDeleteMeanSearch');
Route::get('admin/dictionary/word/delete-mean-search', 'Admin\DictionaryController@deleteMeanSearch');

Route::get('admin/dictionary/word/delete-mean/{id}', 'Admin\DictionaryController@deleteMean');

Route::get('admin/dictionary/{lang}', 'Admin\DictionaryController@lookedUp');
Route::get('admin/dictionary/edit/{id}', 'Admin\DictionaryController@edit');
Route::get('admin/dictionary/refresh/{id}', 'Admin\DictionaryController@refresh');
Route::post('admin/dictionary/save', 'Admin\DictionaryController@save');
Route::get('admin/dictionary/delete/{id}', 'Admin\DictionaryController@delete');

//pronunciation
Route::get('admin/pronunciation', 'Admin\PronunciationController@index');
Route::get('admin/pronunciation/delete/{cat_id}', array(
    'as' => 'pronunciation.delete_cat',
    'uses' => 'Admin\PronunciationController@deleteCat'
));
Route::get('admin/pronunciation/edit/{cat_id}', array(
    'as' => 'pronunciation.edit_cat',
    'uses' => 'Admin\PronunciationController@getCat'
));
Route::get('admin/pronunciation/create_cat', array(
    'as' => 'pronunciation.create_cat',
    'uses' => 'Admin\PronunciationController@createCat'
));
Route::post('admin/pronunciation/save_cat', 'Admin\PronunciationController@postCat');

Route::post('admin/pronunciation/edit/{cat_id}', 'Admin\PronunciationController@index');
Route::get('admin/pronunciation/voc/{cat_id}', array(
    'as' => 'pronunciation.vocs',
    'uses' => 'Admin\PronunciationController@vocs'
));



Route::get('admin/pronunciation/edit_voc/{voc_id}', array(
    'as' => 'pronunciation.edit_voc',
    'uses' => 'Admin\PronunciationController@getVoc'
));
Route::get('admin/pronunciation/create_voc', array(
    'as' => 'pronunciation.create_voc',
    'uses' => 'Admin\PronunciationController@createVoc'
));
Route::post('admin/pronunciation/save_voc', 'Admin\PronunciationController@postVoc');
Route::get('admin/pronunciation/delete_voc/{voc_id}', array(
    'as' => 'pronunciation.delete_voc',
    'uses' => 'Admin\PronunciationController@deleteVoc'
));
Route::get('admin/pronunciation/crawl-oxford/{voc_id}', array(
    'as' => 'pronunciation.crawl_voc',
    'uses' => 'Admin\PronunciationController@getOxford'
));
Route::get('admin/pronunciation/crawl-oxfords', array(
    'as' => 'pronunciation.crawl_vocs',
    'uses' => 'Admin\PronunciationController@getOxfords'
));
Route::get('admin/pronunciation/crawl-oxford-by-link', array(
    'as' => 'pronunciation.crawl_voc_link',
    'uses' => 'Admin\PronunciationController@getOxfordLink'
));

Route::group(['prefix' => 'admin/pron-question', "namespace" => "Admin"], function () {
    Route::get('/list/{cat_id}', array(
        'as' => 'Pronunciation.list_question',
        'uses' => 'QuestionController@listQuestions'
    ));
    Route::get('/edit-question/{question_id}', array(
        'as' => 'PronQuestion.edit_question',
        'uses' => 'QuestionController@getQuestion'
    ));
    Route::get('/create-question', array(
        'as' => 'PronQuestion.create_question',
        'uses' => 'QuestionController@createQuestion'
    ));
    Route::post('/save-question', array(
        'as' => 'PronQuestion.save_question',
        'uses' => 'QuestionController@postQuestion'
    ));
    
     Route::get('/ajax-publish-question', array(
        'as' => 'PronQuestion.ajax_publish_question',
        'uses' => 'QuestionController@ajaxPublishQuestion'
    ));
   
});



//Route::get('admin/looked-up/crawl', 'Admin\AdminController@getPronunciation');
Route::get('api/looked-up/crawl', 'Api\ApiController@getPronunciation');
Route::get('api/looked-up/crawl-globe', 'Api\ApiController@getGlobe');
Route::get('api/looked-up/crawl-globe-web', 'Api\ApiController@getGlobeWeb');
//crawl
Route::group(['prefix' => 'crawl', "namespace" => "Crawl"], function () {
    Route::group(['prefix' => 'images'], function () {
        Route::get('hair-buzz', 'ImageController@getHairTheLatest');
        Route::get('hair-latest', 'ImageController@getHairTheLatest');
    });
    Route::group(['prefix' => 'minder'], function () {
        Route::get('courses', 'MinderController@courses');
        Route::get('subjects/{course_id}', 'MinderController@subjects');
    });
});
/**
 * images
 */
//categories
Route::group(['prefix' => 'admin/img', 'namespace' => 'Admin'], function () {
    Route::get('cats', array(
        'as' => 'image.cats',
        'uses' => 'ImageCatController@index'
    ));
    Route::get('cats/{cat_id}', array(
        'as' => 'image.catsId',
        'uses' => 'ImageCatController@index'
    ));
    Route::get('cat/create', array(
        'as' => 'image.createCat',
        'uses' => 'ImageCatController@create'
    ));
    Route::post('cat/save', array(
        'as' => 'image.saveCat',
        'uses' => 'ImageCatController@update'
    ));
    Route::get('cat/edit/{id}', array(
        'as' => 'image.editCat',
        'uses' => 'ImageCatController@edit'
    ));
    Route::get('cat/delete/{id}', array(
        'as' => 'image.deleteCat',
        'uses' => 'ImageCatController@delete'
    ));

    Route::group(['prefix' => 'images'], function () {
        Route::get('', 'ImageController@index');
        Route::get('list/{cat_id}', array(
            'as' => 'image.listItem',
            'uses' => 'ImageController@items'
        ));
        Route::get('/create', array(
            'as' => 'image.createImg',
            'uses' => 'ImageController@create'
        ));
        Route::post('/save', array(
            'as' => 'image.saveImg',
            'uses' => 'ImageController@update'
        ));
        Route::get('/edit/{id}', array(
            'as' => 'image.editImg',
            'uses' => 'ImageController@edit'
        ));
        Route::get('/delete/{id}', array(
            'as' => 'image.deleteImg',
            'uses' => 'ImageController@delete'
        ));
        Route::post('/delete-imgs', array(
            'as' => 'image.deleteImgs',
            'uses' => 'ImageController@deleteImgs'
        ));

        Route::post('/saveThumb', array(
            'as' => 'image.thumbImg',
            'uses' => 'ImageController@postThumbImage'
        ));
    });
});
Route::group(['prefix' => 'admin/grammar', 'namespace' => 'Admin'], function () {
    Route::get('/', array(
        'as' => 'grammar.index',
        'uses' => 'GrammarController@index'
    ));
    Route::get('/create-cat', array(
        'as' => 'grammar.create_cat',
        'uses' => 'GrammarController@createCat'
    ));
    Route::get('/edit-cat/{cat_id}', array(
        'as' => 'grammar.edit_cat',
        'uses' => 'GrammarController@getCat'
    ));
    Route::get('/delete-cat/{cat_id}', array(
        'as' => 'grammar.delete_cat',
        'uses' => 'GrammarController@deleteCat'
    ));
    Route::post('/save-cat', array(
        'as' => 'grammar.save_cat',
        'uses' => 'GrammarController@postCat'
    ));
    Route::get('/lessons/{cat_id}', array(
        'as' => 'grammar.lessons',
        'uses' => 'GrammarController@lessons'
    ));
    Route::get('/create-lesson', array(
        'as' => 'grammar.create_lesson',
        'uses' => 'GrammarController@createLesson'
    ));
    
    Route::get('/edit-lesson/{lesson_id}', array(
        'as' => 'grammar.edit_lesson',
        'uses' => 'GrammarController@getLesson'
    ));
    Route::post('/ajax-add-question-lesson', array(
        'as' => 'grammar.add_question_lesson',
        'uses' => 'GrammarController@postAddLessonQuestion'
    ));
    Route::get('autocomplete-cat',  array(
        'as' => 'grammar.ajax_get_cats',
        'uses' => 'GrammarController@ajaxGetCats'
    ));

     Route::post('/ajax-add-question-cat', array(
        'as' => 'grammar.add_question_cat',
        'uses' => 'GrammarController@postAddCatQuestion'
    ));
    Route::get('/delete-lesson-question', array(
        'as' => 'grammar.delete_lesson_question',
        'uses' => 'GrammarController@deleteLessonQuestion'
    ));
    Route::get('/delete-cat-question', array(
        'as' => 'grammar.delete_cat_question',
        'uses' => 'GrammarController@deleteCatQuestion'
    ));
    Route::post('/save-lesson', array(
        'as' => 'grammar.save_lesson',
        'uses' => 'GrammarController@postLesson'
    ));
    
    Route::get('/list-cat-questions/{cat_id}', array(
        'as' => 'grammar.list_cat_question',
        'uses' => 'GrammarController@listCatQuestions'
    ));
    
    Route::get('/list-lesson-questions/{cat_id}', array(
        'as' => 'grammar.list_lesson_question',
        'uses' => 'GrammarController@listLessonQuestions'
    ));
    
    
     Route::get('/edit-question/{question_id}', array(
        'as' => 'grammar.edit_question',
        'uses' => 'GrammarController@getQuestion'
    ));
    Route::get('/create-question', array(
        'as' => 'grammar.create_question',
        'uses' => 'GrammarController@createQuestion'
    ));
    Route::post('/save-question', array(
        'as' => 'grammar.save_question',
        'uses' => 'GrammarController@postQuestion'
    ));
    
     Route::get('/ajax-publish-question', array(
        'as' => 'PronQuestion.ajax_publish_question',
        'uses' => 'GrammarController@ajaxPublishQuestion'
    ));
    
});
//   quotes
Route::get('crawl/quotes', 'Crawl\QuoteController@index');
/**
 * create menu
 */
$menu = Menu::make('MyNavBar', function($menu) {
            $menu->add('Home', 'admin')->attr(array('pre_icon' => 'user'));
              //Pronunciation
            $menu->add('Pronunciation', 'admin/pronunciation')->attr(array('pre_icon' => 'bullhorn'))->active('admin/pronunciation/*');
              //Grammar
            $menu->add('Grammar', 'admin/grammar')->attr(array('pre_icon' => 'book'))->active('admin/grammar/*');
               //idioms
            $menu->add('Listening', 'listening')->attr(array('pre_icon' => 'phone'))->active('admin/listening/*');
            $menu->listening->add('Cat', 'admin/listening')->attr(array('pre_icon' => 'phone'))->active('admin/listening/*');
            $menu->listening->add('Reports', 'admin/listening/reports')->attr(array('pre_icon' => 'report'))->active('admin/listening/reports/*');

            
            $menu->add('IELTS', 'admin/ielts')->attr(array('pre_icon' => 'bar-chart-o'))->active('admin/ielts/*');
            $menu->iELTS->add('Types', 'admin/ielts')->attr(array('pre_icon' => 'user'));
            $menu->iELTS->add('Vocabulary', 'admin/ielts/vocabulary')->attr(array('pre_icon' => 'user'));
            foreach (App\Models\IELTS\IELTSCat::where("type", "article")->get() as $cat) {
                $menu->iELTS->add($cat->title, 'admin/ielts/cat/' . $cat->id);
            }
            $menu->add('Content', '')->attr(array('pre_icon' => 'file-text'))->active('admin/categories/*|admin/articles/*');
            $menu->content->add('Categories', 'admin/categories')->attr(array('pre_icon' => 'tag'));
            $i = 0;
            foreach (\App\Models\Categories::where("parent_id", 0)->get() as $cat) {
                $nick_name = str_slug("cat " . $cat->name, '-');
                $menu->content->add($cat->name, 'admin/categories/' . $cat->id)->nickname($nick_name);

                foreach (\App\Models\Categories::where("parent_id", $cat->id)->get() as $cat1) {
                    $menu->item($nick_name)->add($cat1->name, 'admin/categories/' . $cat1->id)->attr(array('pre_icon' => 'tag'));
                }
            }

            $menu->add('Story')->attr(array('pre_icon' => 'book'))->active('admin/story/*');
            $menu->story->add('Cats', 'admin/story/cats');
            $menu->story->add('Crawl Subs', 'admin/story/crawl-pl')->append('<span class="label label-primary pull-right"><span class="fa fa-download"></span></span>');

            /** youtube videos * */
            $menu->add('Video')->attr(array('pre_icon' => 'youtube'))->active('admin/youtube/*');
            foreach (\App\library\Menus::getCats() as $cat) {
                if($cat->id == 5){
                    $menu->video->add($cat->title, 'admin/youtube/en-cats');
                }else{
                    $menu->video->add($cat->title, 'admin/youtube/playlists/' . $cat->id);
                }
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

         
            //users
            $menu->add('Users Manager', 'users')->attr(array('pre_icon' => 'user'));
            $menu->usersManager->add('Users', 'admin/users')->attr(array('pre_icon' => 'user'))->active('admin/users/*');
            $menu->usersManager->add('Permissions', 'admin/user/permissions')->attr(array('pre_icon' => 'user'))->active('admin/user/permission/*');
            $menu->usersManager->add('Roles', 'admin/user/roles')->attr(array('pre_icon' => 'users'))->active('admin/user/role/*');
            $menu->usersManager->add('Profile', 'admin/user/profile')->attr(array('pre_icon' => 'envelope'));

            $menu->add('Pic Voc', 'admin/picvoc/cats')->attr(array('pre_icon' => 'image'))->active('admin/picvoc/*');
            $menu->picVoc->add('Cats', 'admin/picvoc/cats');
            $menu->picVoc->add('Means', 'admin/picvoc/means');
            $menu->picVoc->add('Crawl', 'admin/picvoc/crawl-quizlet');



//            $menu->pronunciation->add('Cat', 'admin/pronunciation')->attr(array('pre_icon' => 'volume-up'))->active('admin/pronunciation/*');
//            $menu->listening->add('Reports', 'admin/listening/reports')->attr(array('pre_icon' => 'report'))->active('admin/listening/reports/*');
//            $menu->get("German Grammar")->add('TiengNhat', 'admin/categories');
            //Lam dep
            $menu->add('Lam Dep', 'makeup')->attr(array('pre_icon' => 'female'))->active('admin/makeup/*');
            $menu->lamDep->add('Cat', 'admin/makeup/cat')->attr(array('pre_icon' => 'female'))->active('admin/makeup/cat/*');
            $menu->lamDep->add('Article', 'admin/makeup/articles')->attr(array('pre_icon' => 'phone'))->active('admin/makeup/article/*');
            $menu->add('Looked Up', 'admin/dictionary')->attr(array('pre_icon' => 'search'))->active('admin/dictionary/*');
            $menu->lookedUp->add('Over View', 'admin/dictionary')->attr(array('pre_icon' => 'search'))->active('admin/dictionary/*');
            $menu->lookedUp->add('Search', 'admin/dictionary/search')->attr(array('pre_icon' => 'search'))->active('admin/dictionary/search');

            $menu->add('Images', 'img')->attr(array('pre_icon' => 'phone'))->active('admin/img/*');
            $menu->images->add('Categories', 'admin/img/cats')->attr(array('pre_icon' => 'phone'))->active('admin/img/cats');
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
    Route::get("en-playlists/{catid}", 'ApiController@getEnPlaylists');
    Route::get("en-videos/{id}", 'ApiController@getEnVideos');

    Route::post("auth/login", 'ApiLoginController@login');
    Route::post("auth/create-user", 'ApiLoginController@createUser');

    /**
     * listening
     */
    Route::group(['prefix' => 'listening'], function () {
        Route::get("/dialogs", 'ListeningController@index');
        Route::get("/cats", 'ListeningController@cats');
        Route::get("/dialogs/{cat_id}", 'ListeningController@getDialogsOfCat');
        Route::get("/dialog/{id}", 'ListeningController@getDialog');
        Route::get('vote', 'ListeningController@setVote');

        Route::get('duration', 'ListeningController@setDucations');
    });

 
    /**
     * idioms
     */
    Route::group(['prefix' => 'idiom'], function () {
        Route::get('/cats', 'IdiomController@cats');
        Route::get('/idioms/{cat_id}', 'IdiomController@getIdiomByCat');
        Route::get('vote', 'IdiomController@setVote');
    });
    /**
     * images
     */
    Route::group(['prefix' => 'images'], function () {
        Route::get("/cats", 'ImageController@allCats');
        Route::get("/cats/{cat_id}", 'ImageController@cats');
        Route::get("/list/{cat_id}", 'ImageController@getImagesByCat');
        Route::get("/image/{id}", 'ImageController@image');
        Route::get('vote', 'ImageController@setVote');
    });

    Route::get('grammar/cat-tests', 'EnglishGrammarController@catTests');
    Route::get('grammar/numb-cat-questions/{id}', 'EnglishGrammarController@numbCatQuestion');
    Route::get('grammar/numb-lesson-questions/{id}', 'EnglishGrammarController@numbLessonQuestion');
    Route::get('grammar/getTest/{id}/{from}', 'EnglishGrammarController@getTest');
    
 
    Route::get('ielts/cats', 'IELTSController@index');
    Route::get('ielts/articles', 'IELTSController@getArticles');
    Route::post('ielts/articles', 'IELTSController@getArticles');

    Route::get('ielts/articles/{cat_id}', 'IELTSController@getArticlesByCat');
    Route::post('ielts/articles/{cat_id}', 'IELTSController@getArticlesByCat');

    Route::get('ielts/article/{id}', 'IELTSController@getArticle');



    Route::get('stories', 'StoryController@index');
    Route::group(['prefix' => 'full-story'], function () {

        Route::get('cats', 'StoryController@getLangCats');
        Route::get('stories/{cat_id}', 'StoryController@getStoriesByCat');
        Route::get('vote', 'StoryController@setVote');
    });
    Route::group(['prefix' => 'story/en'], function () {
        Route::get('duration', 'StoryController@setDucations');

        Route::get('cats', 'StoryController@getCats');
        Route::get('stories/{cat_id}', 'StoryController@getStoriesByCat');
        Route::get('vote', 'StoryController@setVote');
    });
    /** tieng nhat * */
    Route::get('tieng-nhat/cats', 'TiengNhatController@cats');
    Route::get('tieng-nhat/lessons/list/{cat_id}', 'TiengNhatController@lessons');
    Route::get('tieng-nhat/lesson/{id}', 'TiengNhatController@lesson');
    Route::get('tieng-nhat/vote', 'TiengNhatController@setVote');
    Route::get('tieng-nhat/tests/cat/{cat_id}', 'TiengNhatController@testsByCat');
    Route::get('tieng-nhat/test/{test_id}', 'TiengNhatController@test');

    /** tieng nhat * */
    Route::get('content/cats/{parent_id}', 'ContentController@cats');
    Route::get('content/lessons/list/{cat_id}', 'ContentController@lessons');
    Route::get('content/lesson/{id}', 'ContentController@lesson');
    Route::get('content/tests/cat/{cat_id}', 'ContentController@testsByCat');
    Route::get('content/test/{test_id}', 'ContentController@test');
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
 
Route::group(['prefix' => 'trade'], function () {
    Route::get('/total', array(
        'as' => 'trade.total',
        'uses' => 'HomeController@xlm'
    ));
    Route::get('/boll', array(
        'as' => 'trade.coins',
        'uses' => 'HomeController@boll'
    ));
});

Route::group(['prefix' => 'face'], function () {

    Route::get('/save-ad', array(
        'as' => 'face.saveAd',
        'uses' => 'HomeController@saveAd'
    ));
    Route::get('/cron', array(
        'as' => 'face.saveCron', 
        'uses' => 'HomeController@saveCron'
    ));
 /*
    Build HTML contact form
 */
Route::get('buildform',function(){
    return view('buildform');
 });
});