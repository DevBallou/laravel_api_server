<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new \Illuminate\Http\JsonResponse([
            'data' => 'index'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        return new \Illuminate\Http\JsonResponse([
            'data' => 'store'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return new \Illuminate\Http\JsonResponse([
            'data' => 'show'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Comment $comment)
    {
        return new \Illuminate\Http\JsonResponse([
            'data' => 'update'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        return new \Illuminate\Http\JsonResponse([
            'data' => 'destroy'
        ]);
    }
}
