@extends('layouts.app')

@section('title', '404 Not Found')

@section('content')
    <div class="text-center py-20">
        <h1 class="text-5xl font-bold text-indigo-600 mb-4">404</h1>
        <p class="text-gray-600 text-lg mb-6">Oops! The blog post you're looking for doesn't exist.</p>
        <a href="{{ url('/blogs') }}" class="text-indigo-600 hover:underline font-medium">
            ← Back to Blog List
        </a>
    </div>
@endsection
