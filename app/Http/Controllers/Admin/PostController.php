<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index', ['posts' => Post::orderByDesc('updated_at')->paginate(20)]);
    }

    public function create()
    {
        return view('admin.posts.form', ['post' => new Post(['status' => 'draft'])]);
    }

    public function store(Request $request)
    {
        Post::create($this->data($request));

        return redirect()->route('admin.posts.index')->with('ok', __('admin.saved'));
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', ['post' => $post]);
    }

    public function update(Request $request, Post $post)
    {
        $post->update($this->data($request, $post));

        return redirect()->route('admin.posts.index')->with('ok', __('admin.saved'));
    }

    public function destroy(Post $post)
    {
        if ($post->cover_path) {
            Storage::disk('public')->delete($post->cover_path);
        }
        $post->delete();

        return back()->with('ok', __('admin.deleted'));
    }

    private function data(Request $r, ?Post $post = null): array
    {
        $d = $r->validate([
            'title' => ['required', 'string', 'max:160'],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'body' => ['required', 'string', 'max:50000'],
            'status' => ['required', 'in:draft,published'],
            'cover' => ['nullable', 'image', 'max:3072'],
        ]);
        unset($d['cover']);
        $d['slug'] = Post::uniqueSlug($d['title'], $post?->id);
        $d['published_at'] = $d['status'] === 'published' ? ($post?->published_at ?? now()) : null;

        if ($r->hasFile('cover')) {
            if ($post?->cover_path) {
                Storage::disk('public')->delete($post->cover_path);
            }
            $d['cover_path'] = $r->file('cover')->store('posts', 'public');
        }

        return $d;
    }
}
