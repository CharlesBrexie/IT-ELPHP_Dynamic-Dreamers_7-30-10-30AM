<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Requests;

class RequestController extends Controller
{
    public function index()
    {
        return Requests::all();  // Fetch all users from the database
    }

    // Get user by ID
    public function show($requestId)
    {
        return Requests::findOrFail($requestId);  // Fetch user by ID
    }

    public function store(HttpRequest $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'requestId' => 'required|exists:requests,id',
            'donationId' => 'required|exists:donations,id',
            'ngoId' => 'required|exists:ngos,id',
        ]);

        $donation = Requests::create($validated);

        return response()->json($donation, 201);  // Return the created user
    }
}
