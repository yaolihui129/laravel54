<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Post;
class PostController extends Controller
{
    //文章列表
    public function index(){
        $posts = Post::orderBy('created_at','desc')->get();
        return view('posts/index',compact('posts'));
    }
    //文章详情
    public function show(){
        return view('posts/show');
    }
    //创建文章页
    public function create(){
        return view('posts/create');
    }
    //创建文章接口
    public function store(){

    }
    //修改文章接页
    public function edit(){
        return view('posts/edit');
    }
    //修改文章接口
    public function update(){

    }
    //删除文章接口
    public function delete(){

    }
}
