<ul class="nav navbar-nav navbar-left">
    <li><a class="blog-nav-item" href="{{env('APP_PATH', '')}}/posts">首页</a></li>
    <li><a class="blog-nav-item" href="{{env('APP_PATH', '')}}/posts/create">写文章</a></li>
    <li><a class="blog-nav-item" href="{{env('APP_PATH', '')}}/notices">通知</a></li>
    <li><input name="query" type="text" value="" class="form-control" style="margin-top:10px" placeholder="搜索词"></li>
    <li><button class="btn btn-default" style="margin-top:10px" type="submit">Go!</button></li>
</ul>
