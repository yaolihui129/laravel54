<?php

namespace App\Back\Controllers;

class NoticeController extends Controller{
	// 首页
	public function index(){
	   $notices = \App\Model\Notice::all();
	   return view('back/notice/index', compact('notices'));
   }

   public function create(){
	   return view('back/notice/create');
   }

   public function store(){
	   $this->validate(request(), [
		   'title' => 'required|string',
		   'content' => 'required|string',
	   ]);

	   $notice = \App\Model\Notice::create(request(['title', 'content']));

	   dispatch(new \App\Jobs\SendMessage($notice));

	   return redirect("/back/notices");
   }
}
