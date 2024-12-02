<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{

    // get all donations for public view
    public function get()
    {
        try {
            $donations = Donation::all();

            return response()->json([
                'success' => true,
                'data' => $donations
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'There was an error getting all the doantions data',
                'errorMessage' => $e->getMessage()
            ]);
        }
    }

    // / Fetch all donations made by this particular user
    public function index($id)
    {
        try {
            $donations = Donation::where('user_id', $id)->get();
            return response()->json([
                'success' => true,
                'data' => $donations
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'There was an error getting all your donations: Get All Donations',
                'errorMessage' => $e->getMessage()
            ], 500);
        }
    }

    // Get specific donation
    public function show($userId, $donationId)
    {
        try {
            $donation = Donation::where('user_id', $userId)->where('donationId', $donationId)->first();

            if (! $donation) {
                return response()->json([
                    'success' => false,
                    'message' => 'We could not find any donation with this specified id.'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $donation
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'There was an error getting all your donations: Get Donation',
                'errorMessage' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request, $id)
    {
        try {

            // look up if id really exists
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'address' => 'required|string|max:255',
                'itemName' => 'required|string|max:255',
                'quantity' => 'required|integer|max:64',
                'utensilsNeeded' => 'required|boolean',
                'charity' => 'required|string|max:255',
                'timeOfPreparation' => 'required|string|max:255',
            ]);

            //return response()->json(['url' => $validator->validated()["image"]]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ], 400);
            }

            $createdDonation = Donation::create(array_merge($validator->validated(), ['user_id' => $id, 'DateDonated' => now(), 'imageUrl' => 'https://marketplace.canva.com/EAFddFvI0Yg/2/0/900w/canva-pink-organic-illustrative-cat-phone-wallpaper-5OSmmg6aup0.jpg']));

            return response()->json([
                'success' => true,
                'boyd' => $createdDonation
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'We could not find any user related to this id'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'There was an error saving your donation. Try again',
                'errorMessage' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($userId, $donationId)
    {
        try {
            $donation = Donation::where('donationId', $donationId)->where('user_id', (int) $userId)->delete();

            if (!$donation) {
                return response()->json([
                    'message' => 'We could not find any donation under these credentials. Sorry not sorry'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'mesage' => $donation
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'There was an erro deleting your donation. Try again',
                'errorMessage' => $e->getMessage()
            ], 500);
        }
    }
}
