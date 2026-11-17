<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return view('site.posts', ['posts' => Post::published()->paginate(9)]);
    }

    public function show(string $slug)
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        return view('site.post', ['post' => $post]);
    }
}
