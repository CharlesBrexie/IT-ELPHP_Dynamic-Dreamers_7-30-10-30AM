<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\User;
use App\Models\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RequestController extends Controller
{
    public function index($id)
    {
        try {

            // look up if user really exists sa, only allow request from NGO userType
            $user = User::findOrFail($id);

            if ($user->userType !== 'NGO') {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not allowed to perfom such query'
                ], 401);
            }

            // retrieve all the requests made by this specific NGO
            $requests = Request::with('donation')->where('ngoId', $id)->get();

            //cleaned result
            $cleanedResult = $requests->map(function ($request) {
                return [
                    'userId' => $request->ngoId,
                    'donationId' => $request->donationId,
                    'itemName' => $request->donation->itemName,
                    'timeOfPreparation' => $request->donation->timeOfPreparation,
                    'quantity' => $request->donation->quantity,
                    'address' => $request->donation->address,
                    'utensilsNeeded' => $request->donation->utensilsNeeded,
                    'imageUrl' => $request->donation->imageUrl,
                    'charity' => $request->donation->charity,
                    'donatorId' => $request->donation->user_id,
                    'DateDonated' => $request->donation->DateDonated
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $cleanedResult
            ], 200);
        } catch (ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'We could not find any NGO with this id ' . $id
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'There was an error retrieving all your requests. Try again!',
                'errorMessage' => $e->getMessage()
            ], 500);
        }
    }

    // Get user by ID
    public function show($requestId)
    {
        return Request::findOrFail($requestId);  // Fetch user by ID
    }

    public function store($ngoId, $donationId)
    {

        try {
            // look up if both really exists 
            $ngo = User::find($ngoId)->where('userType', 'NGO')->first();

            if (! $ngo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Either you\'re not allowed to perform this action or your data could not be found'
                ], 404);
            }

            $donation = Donation::where('donationId', $donationId)->first();

            if (! $donation) {
                return response()->json([
                    'success' => false,
                    'message' => 'We could not find any related donation data with this id ' . $donationId
                ], 404);
            }

            $request = Request::create([
                'ngoId' => $ngo->id,
                'donationId' => $donation->donationId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Request sent successfully',
                'data' => $request
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'There was an error saving your request. Try again!',
                'errorMessage' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($ngoId, $donationId)
    {
        try {
            $deletedRequest = Request::where('ngoId', $ngoId)->where('donationId', $donationId)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Request deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'There was an error deleting your request',
                'errorMessage' => $e->getMessage()
            ]);
        }
    }
}
