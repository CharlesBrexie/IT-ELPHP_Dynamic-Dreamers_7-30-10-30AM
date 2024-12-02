<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    /**
     * Handle user login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Check if user exists
            $user = User::where('email', $request->input('email'))->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Invalid credentials'], 400);
            }

            // Return success response with the token
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle user registration request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            // Validate registration input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phoneNumber' => 'required|string|min:11',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'userType' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Create a new user
            $user = User::create([
                'name' => $request->name,
                'phoneNumber' => $request->phoneNumber,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'userType' => $request->userType
            ]);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Logout the user and invalidate their token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoke the user's token
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        // Return success response
        return response()->json(['success' => true, 'message' => 'Logged out successfully'], 200);
    }
}
