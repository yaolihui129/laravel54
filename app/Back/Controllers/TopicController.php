<?php

namespace App\Back\Controllers;

class TopicController extends Controller{
   // 首页
   public function index(){
	   $topics = \App\Model\Topic::all();
	   return view('back/topic/index', compact('topics'));
   }

   public function create(){
	   return view('back/topic/create');
   }

   public function store(){
	   $this->validate(request(), [
		   'name' => 'required|string'
	   ]);

	   \App\Model\Topic::create(['name' => request('name')]);

	   return redirect("/back/topics");
   }

   public function destroy(\App\Model\Topic $topic){
	   $topic->delete();
	   return [
		   'error' => 0,
		   'msg' => ''
	   ];
   }
}
