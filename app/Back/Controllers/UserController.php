<?php

namespace App\Back\Controllers;
use Illuminate\Http\Request;
use \App\Model\BackUser;
use \App\Model\BackRole;
class UserController extends Controller{
   // 管理员列表页面
   public function index(){
	   $users = BackUser::paginate(10);
	   return view('/back/user/index', compact('users'));
   }

   // 管理员创建页面
   public function create(){
	   return view('/back/user/add');
   }

   // 创建操作
   public function store(){
	   $this->validate(request(),[
		  'name' => 'required|min:3',
		   'password' => 'required'
	   ]);

	   $name = request('name');
	   $password = bcrypt(request('password'));
	   BackUser::create(compact('name', 'password'));

	   return redirect("/back/users");
   }

   // 用户角色页面
   public function role(\App\Model\BackUser $user){
	   $roles = BackRole::all();
	   $myRoles = $user->roles;
	   return view('/back/user/role', compact('roles', 'myRoles', 'user'));
   }

   // 储存用户角色
   public function storeRole(\App\Model\BackUser $user){
	   $this->validate(request(),[
		   'roles' => 'required|array'
	   ]);

	   $roles = BackUser::findMany(request('roles'));
	   $myRoles = $user->roles;

	   // 要增加的
	   $addRoles = $roles->diff($myRoles);
	   foreach ($addRoles as $role) {
		   $user->assignRole($role);
	   }

	   // 要删除的
	   $deleteRoles = $myRoles->diff($roles);
	   foreach ($deleteRoles as $role) {
		   $user->deleteRole($role);
	   }

	   return back();
   }
}
