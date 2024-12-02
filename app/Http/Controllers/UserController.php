<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Add this at the top of your controller
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Get all users
    public function index()
    {
        return User::all();  // Fetch all users from the database
    }

    /**
     * Fetch user details.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDetails($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update user details.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserDetails(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phoneNumber' => 'required|string|min:11',
                'pfp' => 'nullable|string', // Base64 or URL of profile picture
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Update user details
            $user->update([
                'name' => $request->input('name'),
                'phoneNumber' => $request->input('phoneNumber'),
                'pfp' => $request->input('pfp'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Get user by ID
    public function show($id)
    {
        return User::findOrFail($id);  // Fetch user by ID
    }

    // Create a new user
    public function store(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'UserImage' => 'nullable|string',
            'password' => 'required|string|min:8', // Password validation
        ]);

        // Hash the password before storing
        $validated['password'] = Hash::make($request->password);

        // Create the user with the validated data (including hashed password)
        $user = User::create($validated);

        return response()->json($user, 201);  // Return the created user
    }

    // Update user information
    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'UserImage' => 'nullable|string',
            'password' => 'nullable|string|min:8',  // Password validation (optional for update)
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // If password is provided, hash it before updating
        if ($request->has('password')) {
            $validated['password'] = Hash::make($request->password); // Hash the new password
        }

        // Update the user with the validated data (password will be hashed if provided)
        $user->update($validated);

        return response()->json($user);  // Return the updated user
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);  // Return a 204 status (No content)
    }
}
