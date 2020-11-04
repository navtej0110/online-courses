<?php

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

/**
 * open routes.
 */
Route::get('/', function () {
    return view('welcome');
})->name('home-page');

Route::group(['prefix' => 'laravel-filemanager', /*'middleware' => ['web', 'auth']*/], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

/**
 * language switcher.
 */
Route::get('setlocale/{locale}', function ($locale) {
  if (in_array($locale, \Config::get('app.locales'))) {
    session(['locale' => $locale]);
  }
  return redirect()->back();
})->name('language-switcher');

Auth::routes();

// login, register
Route::get('/login', 'Auth\LoginController@showUserLoginForm')->name('front.login');
Route::post('/login', 'Auth\LoginController@userLogin');
Route::get('/register', 'Auth\RegisterController@showUserRegisterForm')->name('front.register');
Route::post('/register', 'Auth\RegisterController@createUser');

// admin login, register
Route::get('/login/admin', 'Auth\LoginController@showAdminLoginForm')->name('admin.login');
Route::post('/login/admin', 'Auth\LoginController@adminLogin');
Route::get('/register/admin', 'Auth\RegisterController@showAdminRegisterForm')->name('admin.register');
Route::post('/register/admin', 'Auth\RegisterController@createAdmin');

// Password Reset Routes...
Route::get('password/reset/{token?}', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset.token');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

/**
 * front area routing
 */
Route::group(['as' => 'front.', 'middleware' => ['auth']], function () {
    require_once(__DIR__.'/front.php');
});

Route::get('/courses', 'Front\CoursesController@listAll')->name('course.list');

Route::get('/modules/{course_slug}', 'Front\ModulesController@listAll')->name('course.modules')->middleware('user_course_validation');
//Route::get('/course/modules/{course_slug}/chapter/{chapter_slug}', 'Front\ChaptersController@index')->name('full-chapter');

/**
 * admin area routing
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:admin']], function () {
    require_once(__DIR__.'/admin.php');
});

// react routes
Route::view('/headless/{path?}', 'react.app');