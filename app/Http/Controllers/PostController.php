<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $posts = Auth::user()->posts()->latest()->get();
        $query = Auth::user()->posts()->latest();
        if($request->has('search') && $request->search !== null) {
            $query->whereAny(['title', 'content'], 'like', '%' . $request->search . '%');
        }
        $posts = $query->paginate(10)->toArray();
        // dd($posts);
        return Inertia::render('posts/index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = User::get();
        return Inertia::render('posts/create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:225',
            'content' => 'required|string|max:1000',
            'status' => 'required',
            'category' => 'required|string|max:225',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        
        $file = $request->file('image');
        $filePath = $file->store('posts', 'public');

        Post::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'category' => $request->category,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'status' => $request->status,
            'image' => $filePath,
        ]);
        // dd($request->all());

        return to_route('posts.index')->with('message', 'Post Created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return Inertia::render('posts/edit', [
            'postData' => $post,
        ]);
    }

    /**
     * Update  the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:225',
            'content' => 'required|string|max:1000',
            'status' => 'required',
            'category' => 'required|string|max:225',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $filePath = $post->image;
        if($request->has('image') && $request->image !== null) {
            $file = $request->file('image');
            $filePath = $file->store('posts', 'public');
            Storage::disk('public')->delete($post->image);
        }

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'category' => $request->category,
            'image' => $filePath,
        ]);
        return to_route('posts.index')->with('message', 'Post Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();

        return to_route('posts.index')->with('message', 'Post Deleted successfully!');
    }
}
