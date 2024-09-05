<?php
namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function getUserTransactions(int $userId)
    {
        return Transaction::where('user_id', $userId)->get();
    }
}