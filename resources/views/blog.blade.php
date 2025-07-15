@extends('layouts.app')

@section('title', 'Blogs')

@section('content')
    <div class="max-w-5xl mx-auto px-0 py-5">
        <h1 class="text-xl lg:text-3xl font-bold text-gray-900 text-center mb-5">What’s New in Simdea</h1>

        <div id="posts-wrapper" class="flex flex-col space-y-10"></div>

        <div class="flex justify-center mt-12">
            <nav class="inline-flex gap-2" id="pagination-wrapper">
                <!-- Pagination buttons will be rendered here -->
            </nav>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const postsWrapper = document.getElementById('posts-wrapper');
        const paginationWrapper = document.getElementById('pagination-wrapper');
        let currentPage = 1;

        async function fetchPosts(page = 1) {
            postsWrapper.innerHTML = `<p class="text-center text-gray-500">Loading...</p>`;
            paginationWrapper.innerHTML = '';

            try {
                const res = await fetch(`/api/posts?page=${page}`);
                const response = await res.json();

                const posts = response.data.data || [];
                const meta = response.data || {};

                if (!posts.length) {
                    postsWrapper.innerHTML = `<p class="text-center text-gray-500">No blog posts found.</p>`;
                    return;
                }

                postsWrapper.innerHTML = '';
                posts.forEach(post => {
                    postsWrapper.insertAdjacentHTML('beforeend', `
                    <div class="flex flex-col md:flex-row gap-6 border-b pb-6">
                        ${post.heading_image ? `
                                        <div class="md:w-1/3 w-full h-56 md:h-40 overflow-hidden rounded border">
                                            <img src="${post.heading_image}" alt="${post.title}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                                        </div>
                                    ` : ''}
                        <div class="md:w-2/3 w-full flex flex-col justify-between">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-900 mb-2">${post.title}</h2>
                                <p class="text-gray-700 text-sm mb-3">${post.content.substring(0, 160)}...</p>
                            </div>
                            <a href="/blogs/${post.slug}" class="text-indigo-600 text-sm font-medium hover:underline self-start">Read more →</a>
                        </div>
                    </div>
                `);
                });

                // Render pagination buttons
                const totalPages = meta.last_page || 1;
                const current = meta.current_page || 1;

                for (let i = 1; i <= totalPages; i++) {
                    paginationWrapper.insertAdjacentHTML('beforeend', `
                    <button
                        class="px-3 py-1 text-sm border rounded ${
                            i === current
                                ? 'bg-indigo-600 text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-100'
                        }"
                        onclick="changePage(${i})"
                    >${i}</button>
                `);
                }
            } catch (err) {
                postsWrapper.innerHTML = `<p class="text-red-600 text-center">Failed to load posts.</p>`;
            }
        }

        function changePage(pageNum) {
            if (pageNum !== currentPage) {
                currentPage = pageNum;
                fetchPosts(currentPage);
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchPosts();
        });
    </script>
@endpush
