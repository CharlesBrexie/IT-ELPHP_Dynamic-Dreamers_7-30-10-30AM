<?php

use Illuminate\Support\Facades\Hash; // Add this at the top

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Hash the password before storing in the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashing the password
        ]);

        // Optionally, log the user in after registration
        Auth::login($user);

        // Redirect or return a response as needed
        return redirect()->route('home'); // Redirect to home or any other page
    }
}
