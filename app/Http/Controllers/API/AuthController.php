<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['success' => true, 'message' => 'Users retrieved successfully.', 'data' => $users]);
    }

    /**
     * Store/Register a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        $request->validated();

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id' => $request->input('role_id'),
            'password' => Hash::make($request->input('password')),
        ]);

        $token = $user->createToken('registerToken');
        $accessToken = $token->plainTextToken;

        return response()->json(['success' => true, 'message' => 'User registered successfully.', 'data' => ['user' => $user->only('name', 'email'), 'token' => $accessToken]], 201);
    }

    /**
     * Login a newly created resource in storage.
     */
    public function login(LoginRequest $request)
    {
        $request->validated();

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {

            return response()->json(['success' => false, 'message' => 'Invalid credentials', 'errors' => ["error" => ['invalid credentials']]], 403);
        }

        $token = $user->createToken('loginToken');
        $accessToken = $token->plainTextToken;

        return response()->json(['success' => true, 'message' => 'Logged in successfully.', 'data' => ['user' => $user->only('name', 'email'), 'token' => $accessToken]], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if ($user !== null) {
            return response()->json(['success' => true, 'message' => 'User fetched successfully', 'data' => $user], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Associated user not found', 'errors' => ["error" => ['Associated user not found']]], 404);
        }
    }


    /**
     * Logout the specified resource in storage.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['status' => 'success', 'message' => 'Logged out successfully!'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
