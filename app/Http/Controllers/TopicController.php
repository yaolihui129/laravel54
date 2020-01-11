<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Topic;
use App\Model\Post;
use App\Model\PostTopic;
class TopicController extends Controller
{
	//专题详情页
	public function show(Topic $topic){
		// 带文章数的专题
		// $topic= [];
		$topic = Topic::withCount('postTopics')->find($topic->id);
		// 专题的文章列表，按照创建时间倒叙排列，前10个
		$posts = $topic->posts()->orderBy('created_at', 'desc')->take(10)->get();
		// 属于我的文章，但是未投稿
		$myposts =[];
		// $myposts = Post::authorBy(Auth::id())->topicNotBy($topic->id)->get();
		
		return view('topic.show',compact('topic','posts','myposts'));
	}
	//投稿
	public function submit(Topic $topic){
		
		$this->validate(request(),[
		   'post_id' => 'required|array',
		]);

		$post_ids = request('post_ids');
		$topic_id = $topic->id;
		foreach ($post_ids as $post_id) {
			PostTopic::firstOrCreate(compact('topic_id', 'post_id'));
		}
		return back();
	}
}
