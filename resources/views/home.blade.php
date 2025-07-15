@extends('layouts.app')

@section('title', 'Blogs')

@section('content')
    <!-- Hero Section -->
    <section class="bg-indigo-600 text-white py-16 px-4 text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Discover Inspiring Stories</h1>
            <p class="lead mb-4">Read, write, and explore meaningful blog posts from our creative writers.</p>
            <a href="{{ url('/blogs') }}" class="btn btn-light text-indigo-700 fw-semibold shadow px-4 py-2 rounded">
                Browse Blogs
            </a>
        </div>
    </section>

    <!-- About / Features -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <div class="p-4 border rounded shadow-sm h-100">
                        <i class="bi bi-pencil-square display-5 text-indigo-600 mb-3"></i>
                        <h5 class="fw-bold">Write Blogs</h5>
                        <p class="text-muted">Share your thoughts and stories with a like-minded community.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border rounded shadow-sm h-100">
                        <i class="bi bi-eyeglasses display-5 text-indigo-600 mb-3"></i>
                        <h5 class="fw-bold">Read & Explore</h5>
                        <p class="text-muted">Browse diverse content and find your next favorite read.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border rounded shadow-sm h-100">
                        <i class="bi bi-stars display-5 text-indigo-600 mb-3"></i>
                        <h5 class="fw-bold">Stay Inspired</h5>
                        <p class="text-muted">Get motivated by unique perspectives and success stories.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-gray-50 text-center">
        <div class="container">
            <h2 class="fw-bold text-indigo-700 mb-3">Start Your Blogging Journey</h2>
            <p class="text-muted mb-4">Join Simdea Blog to express your voice and connect with readers around the world.</p>
            <a href="{{ url('/blogs') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded shadow-sm hover:bg-indigo-700 transition">
                Read Blogs
            </a>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- Bootstrap Icons CDN (if not already included) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush
