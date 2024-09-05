<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    // Admin method to create a new user
    public function createUser(Request $request)
    {
        // Validate input data
        $request->validate([
            'name' => 'required|string|max:255',
            'debit_card_number' => 'required|digits:16|unique:users',
            'pin' => 'required|digits:4',
            'email' => 'required|Email',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'debit_card_number' => $request->debit_card_number,
            'pin' => Hash::make($request->pin),
            'email' => $request->email,
            'password'=>bcrypt(random_int(4,6)) // Hash the PIN for security
        ]);

        // Return success response
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    // Admin method to get all transactions
    public function getAllTransactions()
    {
        // Fetch all transactions (assuming you have a Transaction model)
        $transactions = Transaction::with('user')->get(); // Including related user data
        
        // Return transactions with related user information
        return response()->json([
            'transactions' => $transactions
        ], 200);
    }

    public function adminLogin(Request $request)
    {
        // Validate email and password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Attempt to authenticate the admin using email and password
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Get the authenticated user
        $user = Auth::user();
        
        // Check if the user is an admin
        if (!$user->role_id == 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Create a token for the admin
        $token = $user->createToken('Admin-Token')->accessToken;

        return response()->json([
            'message' => 'Admin login successful',
            'token' => $token,
        ], 200);
    }
}
