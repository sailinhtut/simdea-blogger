<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;


// Public pages
Route::view('/', 'home')->name('home');
Route::view('/blogs', 'blog')->name('blog');
Route::get('/blogs/{slug}', function ($slug) {
    $post = Post::where('slug', $slug)->first();

    if (!$post) {
        abort(404, 'Blog not found');
    }

    return view('blog-detail', ['post' => $post]);
});

Route::get('/reset-password', function () {
    return view('auth.password-reset');
})->name('password.reset');