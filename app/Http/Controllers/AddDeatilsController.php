<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AddDetails; // Your model
use Illuminate\Support\Facades\Validator;

class AddDetailsController extends Controller
{
    public function adddetails(Request $request)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:add_details,email|max:255',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|min:10',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Upload photo to public/photos
            $photoName = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $photoName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('photos'), $photoName);
            }

            // Save data
            $addDetails = AddDetails::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone,
                'photo' => $photoName,
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $addDetails
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
