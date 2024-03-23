<?php

namespace App\Http\Controllers\API;

use App\Exceptions\GeneralJsonException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostSharedNotification;
use App\Repositories\PostRepository;
use App\Rules\IntegerArray;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

/**
 * @group Post Management
 * APIs to manage post.
 */
class PostController extends Controller
{
    /**
     * Display a listing of posts.
     * 
     * Gets a list of posts.
     * 
     * @queryParam page_size int Size per page. Defaults to 20. Example: 20
     * @queryParam page int Page to view. Example: 1
     * 
     * @apiResourceCollection App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     * @return ResourceCollection
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
     * Store a newly created post in storage.
     * 
     * @bodyParam title string required Title of the post. Example: Amazing Post
     * @bodyParam body string[] required Body of the post. Example: ["This post is super beautiful"]
     * @bodyParam user_ids int[] required the author ids of the post. Example: [1, 2]
     * @apiResource App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     * 
     * @param  Request $request
     * @return PostResource
     */
    public function store(Request $request, PostRepository $repository)
    {
        $payload = $request->only([
            'title',
            'body',
            'user_ids',
        ]);
        Validator::validate(
            $payload,
            [
                'title' => 'string|required',
                'body' => ['string', 'required'],
                'user_ids' => [
                    'array',
                    'required',
                    new IntegerArray
                ],
            ],
            [
                'title.string' => "HEYYY use a string",
                'body.required' => "Please enter a value for body."
            ],
            [
                'user_ids' => 'USERR IDS'
            ]
        );

        $created = $repository->create($payload);

        return new PostResource($created);
    }

    /**
     * Display the specified post.
     * 
     * @urlParam id int required Post ID
     * @apiResource App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     * 
     * @param  \App\Models\Post $post
     * @return PostResource
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
     * Update the specified post in storage.
     * 
     * @bodyParam title string required Title of the post. Example: Amazing Post
     * @bodyParam body string[] required Body of the post. Example: ["This post is super beautiful"]
     * @bodyParam user_ids int[] required the author ids of the post. Example: [1, 2]
     * @apiResource App\Http\Resources\PostResource
     * @apiResourceModel App\Models\Post
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return PostResource | JsonResponse
     */
    public function update(Request $request, Post $post, PostRepository $repository)
    {
        $payload = $request->only([
            'title',
            'body',
            'user_ids',
        ]);

        $post = $repository->update($post, $payload);

        return new PostResource($post);
    }

    /**
     * Remove the specified post from storage.
     * 
     * @response 200 {
     *      "data": "success"
     * }
     * 
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post, PostRepository $repository)
    {
        $post = $repository->forceDelete($post);

        return new \Illuminate\Http\JsonResponse([
            'data' => 'Successfully'
        ]);
    }

    /**
     * Share a specified post from storage.
     * 
     * @response 200 {
     *      "data": "signed url..."
     * }
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function share(Request $request, Post $post)
    {
        $url = URL::temporarySignedRoute('shared.post', now()->addDays(30), [
            'post' => $post->id,
        ]);

        $users = User::query()->whereIn('id', $request->user_ids)->get();

        Notification::send($users, new PostSharedNotification($post, $url));

        $user = User::query()->find(1);
        $user->notify(new PostSharedNotification($post, $url));
        return new \Illuminate\Http\JsonResponse([
            'data' => $url,
        ]);
    }
}
