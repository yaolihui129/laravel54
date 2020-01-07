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

Route::get('/', function () {
    return redirect('/login');
});
//用户模块
//注册页面
Route::get('/register','RegisterController@index' );
//注册行为
Route::post('/register','RegisterController@register' );
//登录页面
Route::get('/login','LoginController@index' )->name("login");
//登录行为
Route::post('/login','LoginController@login' );

Route::group(['middleware'=>'auth:web'],function(){
	//登出行为
	Route::get('/logout','LoginController@logout' );
	
	//个人设置
	Route::get('/user/me/setting','UserController@setting' );
	//个人设置操作
	Route::post('/user/me/setting','UserController@settingStore' );
	
	//文章列表页
	Route::get('/posts','PostController@index' );
	//创建文章
	Route::post('/posts','PostController@store' );
	Route::get('/posts/create','PostController@create' );
	//更新文章
	Route::get('/posts/{post}/edit','PostController@edit')->where('post','[0-9]+');
	Route::put('/posts/{post}','PostController@update' )->where('post','[0-9]+');
	//删除文章
	Route::get('/posts/{post}/delete','PostController@delete' )->where('post','[0-9]+');
	//文章详情页
	Route::get('/posts/{post}','PostController@show')->where('post','[0-9]+');
	//图片上传
	Route::post('/posts/image/upload','PostController@imageUpload');
});

