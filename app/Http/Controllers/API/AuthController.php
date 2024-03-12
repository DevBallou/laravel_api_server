<?php

namespace App\Http\Controllers\API;

use App\Events\Models\User\UserCreated;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }

    public function index(Request $request)
    {
        event(new UserCreated(User::factory()->make()));
        $pageSize = $request->page_size ?? 10;
        $users = User::query()->paginate($pageSize);

        return UserResource::collection($users);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, UserRepository $repository)
    {
        $created = $repository->create($request->only([
            'name',
            'email',
            'password',
        ]));

        return new UserResource($created);
    }

    /**
     * update
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user, UserRepository $repository)
    {
        $updated = $repository->update($user, $request->only([
            'name',
            'email',
        ]));

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user, UserRepository $repository)
    {
        $deleted = $repository->forceDelete($user);

        return new \Illuminate\Http\JsonResponse([
            'data' => 'Successfully'
        ]);
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => 'required|string',
    //     ]);

    //     $credentials = $request->only('email', 'password');
    //     $token = Auth::attempt($credentials);

    //     if (!$token) {
    //         return response()->json([
    //             'message' => 'Unauthorized',
    //         ], 401);
    //     }

    //     $user = Auth::user();
    //     return response()->json([
    //         'user' => $user,
    //         'authorization' => [
    //             'token' => $token,
    //             'type' => 'bearer',
    //         ]
    //     ]);
    // }

    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:6'
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     return response()->json([
    //         'message' => 'User created successfully',
    //         'user' => $user
    //     ]);
    // }

    // public function logout()
    // {
    //     Auth::logout();
    //     return response()->json([
    //         'message' => 'Successfully logged out',
    //     ]);
    // }

    // public function refresh()
    // {
    //     return response()->json([
    //         'user' => Auth::user(),
    //         'authorisation' => [
    //             'token' => Auth::refresh(),
    //             'type' => 'bearer',
    //         ]
    //     ]);
    // }

    // public function user()
    // {
    //     $user = User::all();

    //     return response()->json([
    //         'user' => $user,
    //     ]);
    // }
}
