<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;

class PostController extends Controller
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
    public function show(Post $post)
    {
        return new \Illuminate\Http\JsonResponse([
            'data' => 'show'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Post $post)
    {
        return new \Illuminate\Http\JsonResponse([
            'data' => 'update'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        return new \Illuminate\Http\JsonResponse([
            'data' => 'destroy'
        ]);
    }
}
