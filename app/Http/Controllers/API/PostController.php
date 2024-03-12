<?php

namespace App\Http\Controllers\API;

use App\Exceptions\GeneralJsonException;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // report('Something went wrong.');
        // abort(404);
        $pageSize = $request->page_size ?? 10;
        $posts = Post::query()->paginate($pageSize);

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return PostResource
     */
    public function store(Request $request, PostRepository $repository)
    {
        $created = $repository->create($request->only([
            'title',
            'body',
            'user_ids',
        ]));

        return new PostResource($created);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post, PostRepository $repository)
    {
        $post = $repository->update($post, $request->only([
            'title',
            'body',
            'user_ids',
        ]));

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, PostRepository $repository)
    {
        $post = $repository->forceDelete($post);

        return new \Illuminate\Http\JsonResponse([
            'data' => 'Successfully'
        ]);
    }
}
