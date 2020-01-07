<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Post;
class PostController extends Controller
{
    //文章列表
    public function index(){
        $posts = Post::orderBy('created_at','desc')->paginate(6);
        return view('posts/index',compact('posts'));
    }
    //文章详情
    public function show(Post $post){
        return view('posts/show',compact('post'));
    }
    //创建文章页
    public function create(){
        return view('posts/create');
    }
    //创建文章接口
    public function store(){
        //1.验证
        $this->validate(request(),[
            'title'=>'required|string|max:100|min:5',
            'content'=>'required|string|min:10',
        ]);
        //2.逻辑
        $user_id = \Auth::id();
        $params = array_merge(request(['title','content']),compact('user_id'));
        $post=Post::create($params);
        //3.渲染
        return redirect("/posts");

    }
    //修改文章接页
    public function edit(Post $post){
        return view('posts/edit',compact('post'));
    }
    //修改文章接口
    public function update(Post $post){
        //1.验证
        $this->validate(request(),[
            'title'=>'required|string|max:100|min:5',
            'content'=>'required|string|min:10',
        ]);
        //用户权限认证
        $this->authorize('update',$post);
        //2.逻辑
        $post->title = \request('title');
        $post->content = \request('content');
        $post->save();
        //3.渲染
        return redirect("/posts/{$post->id}");
    }
    //删除文章接口
    public function delete(Post $post){
        //用户权限认证
        $this->authorize('delete',$post);
        $post->delete();

        return redirect("/posts");
    }
    //上传图片
    public function imageUpload(Request $request){

        $fileName=date('Y_m_d').'/'.md5(time()) .mt_rand(0,9999);
        $path = $request->file('wangEditorH5File')->storePublicly($fileName);
        return asset('storage/'. $path);
    }
}
