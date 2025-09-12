<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        try {
            // / Validate input
            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6', 
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create user
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'user'   => $user
            ], 201);

        } catch (Exception $e) {
            // Catch any unexpected errors
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   // Login User
public function login(Request $request) 
{
    try {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find user
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid email or password bhai',
            ], 401);
        }

        // Create Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'     => 'success',
            'message'    => 'Successfully login bhai',
            'user'       => $user,
            'token'      => $token,
            'token_type' => 'Bearer'
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Login failed',
            'error'   => $e->getMessage()
        ], 500);
    }
}


// logout
public function logout(Request $request){
    $request->user()->currentAccessToken()->delete();
    return response()->json([
        'status' => 'success',
        'message' => 'Logout Sucessfully Bhai'
    ]);
}


}
