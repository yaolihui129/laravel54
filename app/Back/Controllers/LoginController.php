<?php

namespace App\Back\Controllers;

class LoginController extends Controller{
   // 登录展示页
   public function index(){
	   //渲染
	   return view('back.login.index');
   }

   // 登录操作
   public function login(){
	   // 验证
	   $this->validate(request(),[
		   'name' => 'required|min:2',
		   'password' => 'required|min:5|max:10',
	   ]);
	   // 逻辑
	   $user = request(['name', 'password']);
	   if (\Auth::guard("back")->attempt($user)) {
		   return redirect('/back/home');
	   }
	   // 渲染
	   return \Redirect::back()->withErrors("用户名密码不匹配");
   }

   // 登出
   public function logout(){
	   \Auth::guard("back")->logout();
	   return redirect('/back/login');
   }
}
