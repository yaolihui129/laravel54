<?php

namespace App\Model;

use App\Model;

class BackRole extends Model{
    protected $table = "back_roles";
    
	// 当前角色的所有权限
	public function permissions(){
		return $this->belongsToMany(\App\Model\BackPermission::class, 'back_permission_role', 'role_id', 'permission_id')
		->withPivot(['permission_id', 'role_id']);
	}

	// 给角色赋予权限
	public function grantPermission($permission){
		return $this->permissions()->save($permission);
	}

	// 取消角色赋予的权限
	public function deletePermission($permission){
		return $this->permissions()->detach($permission);
	}

	// 判断角色是否有权限
	public function hasPermission($permission){
		return $this->permissions->contains($permission);
	}
}
