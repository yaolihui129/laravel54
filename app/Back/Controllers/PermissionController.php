<?php

namespace App\Back\Controllers;

class PermissionController extends Controller{
   // 权限列表页面
   public function index()
   {
	   $permissions = \App\Model\BackPermission::paginate(10);
	   return view('/back/permission/index', compact('permissions'));
   }

   // 创建权限
   public function create(){
	   return view('/back/permission/add');
   }

   // 创建权限实际行为
   public function store(){
	   $this->validate(request(), [
		   'name' => 'required|min:3',
		   'description' => 'required'
	   ]);

	   \App\Model\BackPermission::create(request(['name', 'description']));
	   return redirect('/back/permissions');
   }
}
