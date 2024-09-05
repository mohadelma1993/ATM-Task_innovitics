<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the input
        $request->validate([
            'debit_card_number' => 'required|string',
            'pin' => 'required|digits:4',
        ]);

        // Attempt to find the user by debit card number
        $user = User::where('debit_card_number', $request->debit_card_number)->first();
        // Check if the user exists and the PIN is correct
        if (!$user || !Hash::check($request->pin,$user->pin)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Generate a token using Laravel Passport or JWT
        $token = $user->createToken('ATM-Token')->accessToken;

        // Return the token
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ], 200);
    }
}
