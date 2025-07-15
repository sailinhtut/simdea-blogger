<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{
    public function index()
    {
        try {
            $data = Post::paginate(10);
            // $data = Post::all();
            return response()->json(['total' => count($data), 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required|string',
            'slug' => 'required|unique:posts,slug',
            'content' => 'required|string',
            'date' => 'required|date',
            'heading_image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048'
        ], [
            'slug.unique' => 'The title has already been taken. Please choose another one.',
            'date.date' => 'Post Date must be in ISO 8601 Format.',
            'heading_image.max' => 'Heading image size must be less than 2 MB.'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        try {
            $heading_image_path = null;

            if ($request->hasFile('heading_image')) {
                $folder = 'posts/' . $request->slug;
                $filename =  'heading_image' . '.' . $request->file('heading_image')->extension();
                $heading_image_path = $request->file('heading_image')->storeAs($folder, $filename, 'public');
            }

            $data = Post::create([
                'title' => $request->title,
                'slug' => $request->slug,
                'content' => $request->content,
                'date' => Carbon::parse($request->date)->toDateTimeString(),
                'heading_image' => $heading_image_path
            ]);
            return response()->json(['message' => 'Post created successfully', 'data' => $data], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(string $slug)
    {
        try {
            $post = Post::where('slug', $slug)->firstOrFail();
            return response()->json($post);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $slug)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'sometimes|string',
            'slug' => 'sometimes|unique:posts,slug,' . $request->slug,
            'content' => 'sometimes|string',
            'date' => 'sometimes|date',
            'heading_image' => 'sometimes|image|mimes:jpg,png,jpeg,gif|max:2048'
        ], [
            'slug.unique' => 'The title has already been taken. Please choose another one.',
            'date.date' => 'Post Date must be in ISO 8601 Format.',
            'heading_image.max' => 'Heading image size must be less than 2 MB.'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        try {
            $post = Post::where('slug', $slug)->firstOrFail();

            if ($request->has('heading_image')) {

                $heading_image_path = null;
                if ($request->hasFile('heading_image')) {
                    $folder = 'posts/' . $request->slug;
                    $filename =  'heading_image' . '.' . $request->file('heading_image')->extension();
                    $heading_image_path = $request->file('heading_image')->storeAs($folder, $filename, 'public');
                    $post->heading_image = $heading_image_path;
                } else {
                    if ($post->heading_image) {
                        Storage::disk('public')->delete($post->heading_image);
                        $post->heading_image = null;
                    }
                }
            }

            $post->update([
                'title' => $request->title ?? $post->title,
                'slug' => $request->slug ?? $post->slug,
                'date' => $request->date ?  Carbon::parse($request->date)->toDateTimeString() : $post->date,
                'content' => $request->content ?? $post->content,
            ]);
            return response()->json(['message' => 'Post updated successfully', 'data' => $post]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $slug)
    {
        try {
            $post = Post::where('slug', $slug)->firstOrFail();

            if ($post->heading_image) {
                
                Storage::disk('public')->deleteDirectory('posts/' . $post->slug);
            }


            $post->delete();
            return response()->json(['message' => 'Post deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred', 'message' => $e->getMessage()], 500);
        }
    }
}