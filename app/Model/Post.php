<?php

namespace App\Model;

use App\Model\Model;

class Post extends Model
{
    //关联用户
    public function user(){

      return $this->belongsTo('App\Model\User','user_id','id');
    }
	
	//关联评论
	public function Comments(){
	
	  return $this->hasMany('App\Model\Comment')->orderBy('created_at','desc');
	}
}
