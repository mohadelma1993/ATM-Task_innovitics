<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ATMController extends Controller
{
    protected $userRepository;
    protected $transactionRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->userRepository = $userRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function deposit(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:0.01']);
        $user = Auth::user();

        $this->userRepository->updateBalance($user->id, $request->amount);

        $this->transactionRepository->create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $request->amount,
        ]);
        $user = Auth::user();
        
        return response()->json([
            'message' => 'Deposit successful',
            'new_balance' => $user->balance+$request->amount
        ], 200);
    }

    public function withdraw(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:0.01']);
        $user = Auth::user();

        if ($user->balance < $request->amount) {
            return response()->json(['error' => 'Insufficient funds'], 400);
        }

        $this->userRepository->updateBalance($user->id, -$request->amount);

        $this->transactionRepository->create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
        ]);
        $user = Auth::user();
        return response()->json([
            'message' => 'Withdrawal successful',
            'new_balance' => $user->balance-$request->amount
        ], 200);
    }

    public function balance()
    {
        $user = Auth::user();
        return response()->json(['balance' => $user->balance], 200);
    }

    public function transactions()
    {
        $user = Auth::user();
        $transactions = $this->transactionRepository->getUserTransactions($user->id);

        return response()->json($transactions, 200);
    }
}
