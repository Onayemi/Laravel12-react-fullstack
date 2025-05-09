<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Notifications\PostNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
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
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            // 'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        
        $file = $request->file('image');
        $filePath = $file->store('posts', 'public');

        $post = Post::create([
            'user_id' => auth()->user()->id,
            'title' => strip_tags($request->title),
            'category' => $request->category,
            'slug' => Str::slug($request->title),
            'content' => strip_tags($request->content),
            'status' => $request->status,
            'image' => $filePath,
        ]);
        $postData = Post::get();
        $data = [
            'title' => 'Fouder of Web IT',
            'date' => date('m/d/Y'),
            'posts' => $postData,
        ];
        // Generate pdf with data 
        $pdf = Pdf::loadView('pdf.generate-product-pdf', $data)->save(public_path('invoice-', time(). rand('9999', '9999999')). Str::random('10'). '.pdf');

        // sending to user
        $user = User::find(auth()->user()->id);
        // $user->notify(new PostNotification($post));

        // sending to email
        Notification::route('mail', [$user['email'] => $user['name']])->notify(new PostNotification($post)); // $pdf
        
        // sending to multiple users
        // $users = User::all();
        // User::chunk(10, function($users) use ($post) {
        //     $recipients = $users->pluck('name', 'email');
        //     Notification::route('mail', $recipients)->notify(new PostNotification($post));
        // });
        
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

        // dd($user);
        $post->update([
            'title' => strip_tags($request->title),
            'content' => strip_tags($request->content),
            'status' => $request->status,
            'category' => $request->category,
            'image' => $filePath,
        ]);

        $six_digit_random_number = random_int(100000, 999999);
        $postCode = $six_digit_random_number;
        // dd($post);
        // sending to user
        $user = User::find(auth()->user()->id); // auth()->user()->email;
        // $user->notify(new PostNotification($post));

        // Sending to mail
        Notification::route('mail', [$user['email'] => $user['name']])->notify(new PostNotification($post)); //, $postCode

        // Sending to multiple email
        // User::chunk(10, function($users) use ($post) {
        //     $recipients = $users->pluck('name', 'email');
        //     Notification::route('mail', $recipients)->notify(new PostNotification($post));
        // });

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
