<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Http\Request;

/**
 * @group Comment Management
 * APIs to manage comments
 */
class CommentController extends Controller
{
    /**
     * Display a listing of comments.
     * 
     * Gets a list of comments.
     * 
     * @queryParam page_size int Size per page. Defaults to 20. Example: 20
     * @queryParam page int Page to view. Example: 1
     * 
     * @apiResourceCollection App\Http\Resources\CommentResource
     * @apiResourceModel App\Models\Comment
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $comments = Comment::query()->paginate($request->page_size ?? 20);

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created comment in storage.
     * 
     * @bodyParam body string[] required Body of the comment. Example: ["This comment is super beautiful"]
     * @bodyParam user_id int required the author id of the comment. Example: 1
     * @bodyParam post_id int required the post id that the comment belongs to. Example: 1
     * @apiResource App\Http\Resources\CommentResource
     * @apiResourceModel App\Models\Comment
     * 
     * @param  Request $request
     * @return CommentResource
     */
    public function store(Request $request, CommentRepository $repository)
    {
        $created = $repository->create($request->only([
            'body',
            'user_id',
            'post_id',
        ]));
        return new CommentResource($created);
    }

    /**
     * Display the specified comment.
     * 
     * @urlParam id int required Comment ID
     * @apiResource App\Http\Resources\CommentResource
     * @apiResourceModel App\Models\Comment
     * 
     * @param  \App\Models\Comment $comment
     * @return CommentResource
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified comment in storage.
     * 
     * @bodyParam body string[] Body of the comment. Example: ["This comment is super beautiful"]
     * @bodyParam user_id int the author id of the comment. Example: 1
     * @bodyParam post_id int the post id that the comment belongs to. Example: 1
     * @apiResource App\Http\Resources\CommentResource
     * @apiResourceModel App\Models\Comment
     * 
     * @param  Request $request
     * @param  \App\Models\Comment $comment
     * @return CommentResource | JsonResponse
     */
    public function update(Request $request, Comment $comment, CommentRepository $repository)
    {
        $comment = $repository->update($comment, $request->only([
            'body',
            'user_id',
            'post_id',
        ]));
        return new CommentResource($comment);
    }

    /**
     * Remove the specified comment from storage.
     * 
     * @response 200 {
     *      "data": "success"
     * }
     * 
     * @param  \App\Models\Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Comment $comment, CommentRepository $repository)
    {
        $deleted = $repository->forceDelete($comment);

        return new \Illuminate\Http\JsonResponse([
            'data' => 'Successfully'
        ]);
    }
}
