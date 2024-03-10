<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::query()->get();
        return new \Illuminate\Http\JsonResponse([
            'data' => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $created = Post::query()->create([
            'title' => $request->title,
            'body' => $request->body,
        ]);
        return new \Illuminate\Http\JsonResponse([
            'data' => $created
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return new \Illuminate\Http\JsonResponse([
            'data' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {

        $updated = $post->update([
            'title' => $request->title ?? $post->title,
            'body' => $request->body ?? $post->body
        ]);

        if (!$updated) {
            return new \Illuminate\Http\JsonResponse([
                'errors' => [
                    'Failed to update model.'
                ]
            ], 400);
        }

        return new \Illuminate\Http\JsonResponse([
            'data' => $post
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $deleted = $post->forceDelete();

        if (!$deleted) {
            return new \Illuminate\Http\JsonResponse([
                'errors' => [
                    'Could not delelete resource.'
                ]
            ]);
        }
        return new \Illuminate\Http\JsonResponse([
            'data' => 'Successfully'
        ]);
    }
}
