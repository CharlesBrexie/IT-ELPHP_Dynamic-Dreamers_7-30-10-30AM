<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Add this at the top of your controller

class UserController extends Controller
{
    // Get all users
    public function index()
    {
        return User::all();  // Fetch all users from the database
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

