<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::get('/home', 'Front\UserController@index')->name('home');
Route::get('/profile', 'Front\UserController@profile')->name('profile');
Route::get('/dashboard', 'Front\UserController@dashboard')->name('dashboard');

Route::group(['prefix' => 'courses', 'as' => 'course.'], function () {
    Route::get('/{course_slug}/{module_slug}/{chapter_slug}', 'Front\ChaptersController@index')->name('module-chapter')->middleware('user_course_module_chapter_validation');
    //Route::post('/authenticate', 'Front\CoursesController@authenticate')->name('authenticate');
    //Route::get('/modules/{course_slug}', 'Front\ModulesController@listAll')->name('modules')->middleware('user_course_validation');
    //Route::get('/list', 'Front\CoursesController@listAll')->name('list');
    //Route::get('/{course_slug}/tests', 'Front\CoursesController@tests')->name('tests');
});

Route::group(['prefix' => 'topic', 'as' => 'topic.'], function () {
    Route::get('/{course_slug}/{module_slug}/{chapter_slug}/{topic_id}', 'Front\TopicController@index')->name('index')->middleware('user_course_module_chapter_validation');
    Route::get('/{course_slug}/{module_slug}/{chapter_slug}/quiz/{topic_id}/', 'Front\TopicController@quiz')->name('quiz')->middleware('user_course_module_chapter_validation');
    Route::post('/mini-quiz/{course_slug}/{module_slug}/{chapter_slug}/{topic_id}', 'Front\TopicController@submitMiniQuiz')->name('mini-quiz')->middleware('user_course_module_chapter_validation');
});

Route::group(['prefix' => 'chapter', 'as' => 'chapter.'], function () {
    Route::get('/quiz/{course_slug}/{module_slug}/{chapter_slug}', 'Front\ChaptersController@quiz')->name('quiz')->middleware('user_course_module_chapter_validation');
    Route::post('/submit-quiz/{course_slug}/{module_slug}/{chapter_slug}', 'Front\ChaptersController@submitQuiz')->name('submit-quiz')->middleware('user_course_module_chapter_validation');
});

Route::group(['prefix' => 'test', 'as' => 'test.'], function () {
    //Route::get('/attempt/{course_slug}/{test_slug}', 'Front\TestController@attempt')->name('attempt');
    //Route::post('/attempt/submit/answer/{course_slug}/{test_slug}', 'Front\TestController@answerSubmit')->name('answersubmit');
});

Route::post('/course/add-to-cart/{course_id}/{user_id}/{course_price}', 'Front\CoursesController@cartFunction')->name('course.cart');
Route::get('/course/cart/', 'Front\CoursesController@cartList')->name('cartList');
Route::get('/course/cart/remove/{item_id}', 'Front\CoursesController@removeCartItem')->name('course.removeCartItem');
Route::get('/course/checkout/{total}', 'Front\CoursesController@checkout')->name('course.checkout');

Route::get('/course/payment/{total}', 'Front\CoursesController@payment')->name('payment');

Route::get('/course/cancel', 'Front\CoursesController@cancel')->name('payment.cancel');

Route::get('/course/success', 'Front\CoursesController@success')->name('payment.success');