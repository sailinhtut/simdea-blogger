@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="max-w-4xl mx-auto px-2 lg:px-6 py-12">

        {{-- Back Button --}}
        <div class="mb-8">
            <a href="{{ url('/blogs') }}"
                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Blogs
            </a>
        </div>

        {{-- Title --}}
        <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $post->title }}</h1>

        {{-- Date --}}
        <p class="text-sm text-gray-500 mb-6">
            Published on {{ \Carbon\Carbon::parse($post->date)->format('F j, Y') }}
        </p>

        {{-- Image --}}
        @if ($post->heading_image)
            <img src="{{ $post->heading_image }}" alt="{{ $post->title }}"
                class="w-full h-72 md:h-96 object-cover rounded-lg mb-8 shadow-md hover:shadow-lg transition-shadow duration-300" />
        @endif

        {{-- Content --}}
        <div class="prose prose-indigo prose-lg max-w-none text-gray-800">
            {!! nl2br(e($post->content)) !!}
        </div>
    </div>
@endsection
