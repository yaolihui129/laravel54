<?php

namespace App\Model;

use App\Model;

class Topic extends Model
{
    //属于这个专题的所有文章
	public function posts(){
		return $this->belongsToMany(\App\Model\Post::class, 'post_topics', 'topic_id', 'post_id');
	}
	
	//属于这个专题的文章数,用于WithCount
	public function postTopics(){
		return $this->hasMany(\App\Model\PostTpoics::class,'topic_id','id');
	}
}
