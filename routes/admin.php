<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Route::get('/404', 'Admin\UserController@fourZeroFour')->name('fourZeroFour');

Route::get('/home', 'Admin\UserController@home')->name('home');
Route::get('/filemanager', 'Admin\UserController@filemanager')->name('filemanager');
Route::get('/profile', 'Admin\ProfileController@home')->name('profile');

Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('/', 'Admin\UserController@index')->name('list');
    Route::post('/listajax', 'Admin\UserController@listAjax')->name('listajax');
    Route::get('/add', 'Admin\UserController@add')->name('add');
    Route::get('/edit/{id}', 'Admin\UserController@edit')->name('edit');
    Route::post('/addupdate/{id?}', 'Admin\UserController@addUpdate')->name('addupdate');
    Route::post('/delete', 'Admin\UserController@delete')->name('delete');
});

Route::group(['prefix' => 'chapters', 'as' => 'chapter.'], function () {
    Route::get('/module/{test_id?}', 'Admin\ChaptersController@index')->name('list');
    Route::get('/add/module/{test_id}', 'Admin\ChaptersController@add')->name('add');
    //Route::get('/tests/{course_id}', 'Admin\ChaptersController@tests')->name('tests');
    Route::post('/modules/addupdatetests/{course_id}', 'Admin\ChaptersController@addupdatetests')->name('addupdatetests');
    Route::get('/edit/module/{test_id}/{id}', 'Admin\ChaptersController@edit')->name('edit');
    Route::post('/addupdate/{test_id}/{id?}', 'Admin\ChaptersController@addUpdate')->name('addupdate');
    Route::post('/delete/{type_id}/{id}', 'Admin\ChaptersController@delete')->name('delete');
});

Route::group(['prefix' => 'pool', 'as' => 'pool.'], function () {
    Route::get('/', 'Admin\QuestionPoolsController@index')->name('list');
    Route::get('/add', 'Admin\QuestionPoolsController@add')->name('add');
    //Route::get('/tests/{course_id}', 'Admin\ChaptersController@tests')->name('tests');
    Route::post('/tests/addupdatetests/{course_id}', 'Admin\QuestionPoolsController@addupdatetests')->name('addupdatetests');
    Route::get('/edit/{id}', 'Admin\QuestionPoolsController@edit')->name('edit');
    Route::post('/addupdate/{id?}', 'Admin\QuestionPoolsController@addUpdate')->name('addupdate');
    Route::post('/delete', 'Admin\QuestionPoolsController@delete')->name('delete');
});

Route::group(['prefix' => 'course', 'as' => 'course.'], function () {
    Route::get('/', 'Admin\CoursesController@index')->name('list');
    Route::get('/add', 'Admin\CoursesController@add')->name('add');
    Route::get('/tests/{course_id}', 'Admin\CoursesController@tests')->name('tests');
    Route::post('/tests/addupdatetests/{course_id}', 'Admin\CoursesController@addupdatetests')->name('addupdatetests');
    Route::get('/edit/{id}', 'Admin\CoursesController@edit')->name('edit');
    Route::post('/addupdate/{id?}', 'Admin\CoursesController@addUpdate')->name('addupdate');
    Route::post('/delete', 'Admin\CoursesController@delete')->name('delete');
});

Route::group(['prefix' => 'modules', 'as' => 'test.'], function () {
    Route::get('/', 'Admin\TestCoursesController@index')->name('list');
    Route::get('/add', 'Admin\TestCoursesController@add')->name('add');
    Route::get('/edit/{id}', 'Admin\TestCoursesController@edit')->name('edit');
    Route::post('/addupdate/{id?}', 'Admin\TestCoursesController@addUpdate')->name('addupdate');
    Route::post('/delete', 'Admin\TestCoursesController@delete')->name('delete');
});

Route::group(['prefix' => 'question', 'as' => 'question.'], function () {
    Route::get('/add/{test_id}', 'Admin\QuestionsController@add')->name('add');
    Route::get('/edit/{test_id}/{id}', 'Admin\QuestionsController@edit')->name('edit');
    Route::post('/addupdate/{id?}', 'Admin\QuestionsController@addUpdate')->name('addupdate');
    Route::post('/delete', 'Admin\QuestionsController@delete')->name('delete');
    Route::post('/locked', 'Admin\QuestionsController@locked')->name('locked');
    Route::get('/test/{test_id?}', 'Admin\QuestionsController@index')->name('list');
});

Route::group(['prefix' => 'topics', 'as' => 'topics.'], function () {
    Route::get('/chapter/{chapter_id?}', 'Admin\TopicsController@index')->name('index');
    Route::post('/addEdit/{chapter_id}/{topic_id?}', 'Admin\TopicsController@addEdit')->name('addEdit');
    Route::get('/add/{chapter_id}', 'Admin\TopicsController@add')->name('add');
    Route::get('/edit/{chapter_id}/{topic_id?}', 'Admin\TopicsController@edit')->name('edit');
});

Route::group(['prefix' => 'question-bank', 'as' => 'question-bank.'], function () {
    Route::get('/', 'Admin\QuestionBankController@index')->name('index');
    Route::get('/get/chapter/{chapter_id}', 'Admin\QuestionBankController@getQuestions')->name('getChapterQuestions');
    
    Route::get('/add/chapter/questions/{chapter_id}', 'Admin\QuestionBankController@addChapterQuestions')->name('addChapterQuestions');
    
    Route::post('/get/chapter/{chapter_id}/{question_id}', 'Admin\QuestionBankController@addEditChapterQuestion')->name('addEditChapterQuestion');
    
    // add edit chapter quiz question
    Route::get('/get/chapter/{chapter_id}/{question_id}', 'Admin\QuestionBankController@getChapterQuestion')->name('getChapterQuestion');
    Route::post('/ajax/get/chapter/question/{chapter_id}/{question_id}', 'Admin\QuestionBankController@ajaxChapterQuestion')->name('ajaxChapterQuestion');
    
    // add edit topic quiz question
    Route::post('/get/topic/{chapter_id}/{topic_id}', 'Admin\QuestionBankController@ajaxTopicQuestion')->name('ajaxTopicQuestion');
    Route::post('/ajax/get/chapter/question/{chapter_id}/{question_id}', 'Admin\QuestionBankController@ajaxChapterQuestion')->name('ajaxChapterQuestion');
    
    Route::post('/addEdit/chapter/{chapter_id}/{question_id?}', 'Admin\QuestionBankController@addEditChapter')->name('addEditChapter');
    Route::post('/addEdit/chapterTopic/{chapter_id}/{topic_id}/{id?}', 'Admin\QuestionBankController@addEditTopic')->name('addEditTopic');
    
    // delete question.
    Route::post('/delete/question/{chapter_id}', 'Admin\QuestionBankController@deleteQuestion')->name('delete');
});
