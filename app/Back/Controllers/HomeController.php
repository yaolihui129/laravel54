<?php

namespace App\Back\Controllers;

class HomeController extends Controller{
   // 首页
   public function index(){
	   //渲染
	   return view('back.home.index');
   }
}
