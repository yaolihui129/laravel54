<?php

namespace App\Model;

use App\Model;

class BackPermission extends Model{
    protected $table = "back_permissions";
    
	// 权限属于哪个角色
	public function roles()
	{
		return $this->belongsToMany(\App\Model\BackRole::class, 'back_permission_role', 'permission_id', 'role_id')
		->withPivot(['permission_id', 'role_id']);
	}
}
