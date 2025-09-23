<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddDetails;
use Illuminate\Support\Facades\Validator;

class AddDetailsController extends Controller
{
    // Store JSON + photo upload
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:add_details,email',
            'address' => 'required|string|max:500',
            'phone'   => 'required|string|max:15',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // photo validation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        // Handle photo upload
        $photoPath = null;
    if ($request->hasFile('photo')) {
    $file = $request->file('photo');
    $filename = $request->name . '.' . $file->getClientOriginalExtension(); // use name field
    $file->move(public_path('photos'), $filename);
    // store relative path
    $photoPath = 'photos/' . $filename;
}

        // Save data
        $details = AddDetails::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'address' => $request->address,
            'phone'   => $request->phone,
            'photo'   => $photoPath
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Details saved successfully',
            'data'    => $details
        ], 201);
    }

    // Fetch all records
    public function index()
    {
        return response()->json(AddDetails::all());
    }
}
