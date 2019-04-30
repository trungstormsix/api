<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', 'Api\LoginController@login');
Route::post('/grammar/saveTest', 'Api\EnglishGrammarController@saveTest');
Route::post('/grammar/saveTestNote', 'Api\EnglishGrammarController@saveTestNote');
Route::get('/grammar/getTestNote', 'Api\EnglishGrammarController@getTestNote');
Route::post('/grammar/getTestNote', 'Api\EnglishGrammarController@getTestNote');
Route::get('/grammar/getUserTest', 'Api\EnglishGrammarController@getUserTest');
Route::get('/grammar/getTotalUserTest', 'Api\EnglishGrammarController@getTotalUserTest');
Route::post('/grammar/getTotalUserTest', 'Api\EnglishGrammarController@getTotalUserTest');
//looked up
//Route::post('/looked-up', 'Api\ApiController@testPost');
Route::post('looked-up', 'Api\ApiController@lookedUp');

Route::post('/looked-up/saveUserWord', 'Api\DicController@saveUserWord');
Route::post('/looked-up/syncUserWords', 'Api\DicController@syncUserWords');
Route::get('/looked-up/syncUserWords', 'Api\DicController@syncUserWords');
Route::get('/looked-up/test', 'Api\DicController@test');
Route::get('/looked-up/get-lang', 'Api\ApiController@getLang');
//ielts
Route::post('/ielts/syncUserWords', 'Api\IELTSController@syncUserWords');//sync user favorite vocabularies
Route::post('/ielts/syncUserWord', 'Api\IELTSController@syncUserWord');
Route::post('/ielts/syncUserNote', 'Api\IELTSController@syncUserNote');
Route::post('/ielts/saveUserVoc', 'Api\IELTSController@saveUserVoc');// save user vocabulary
Route::get('/ielts/test', 'Api\IELTSController@test');

//Route::get('/looked-up/test', 'Api\DicController@test');

Route::get('/quotes/cats', 'Api\QuoteController@cats');
Route::get('/quotes/authors', 'Api\QuoteController@authors');
Route::get('/quotes/author/{author_id}', 'Api\QuoteController@author');
Route::get('/quotes/quote_by_cat/{cat_id}', 'Api\QuoteController@quoteByCat');
Route::get('/quotes/quote_by_author/{auth_id}', 'Api\QuoteController@quoteByAuthor');
Route::post('/quotes/syncLike', 'Api\QuoteController@setVote');

//listening
Route::post('/listening/report', 'Api\ListeningController@report');//save report
 
  

//pronunciation
Route::group(['prefix' => 'pronu', 'namespace' => 'Api'], function () {
    Route::get('/cats', 'PronunciationController@cats');
    Route::get('/vocs/{cat_id}', 'PronunciationController@vocByCat');
    Route::get('/vote-voc', 'PronunciationController@setVoteVoc');
    Route::get('/vote-cat', 'PronunciationController@setVoteCat');
});

