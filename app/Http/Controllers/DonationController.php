<?php

namespace App\Http\Controllers;

use App\Models\Donations;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    // Get all users
    public function index()
    {
        return Donations::all();  // Fetch all users from the database
    }

    // Get user by ID
    public function show($donationId)
    {
        return Donations::findOrFail($donationId);  // Fetch user by ID
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'donationId' => 'required|exists:donations,id',
            'Address' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'image' => 'nullable|string',
            'quantity' => 'required|integer|max:64',
            'utensils_required' => 'required|boolean',
            'charity' => 'required|string|max:255',
            'timeOfPreparation' => 'required|string|max:255',
            'user_Id' => 'required|exists:users,id',
        ]);

        $donation = Donations::create($validated);

        return response()->json($donation, 201);  // Return the created user
    }
}
