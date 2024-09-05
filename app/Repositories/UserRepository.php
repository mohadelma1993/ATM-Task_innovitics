<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByCardNumber(string $cardNumber): ?User
    {
        return User::where('debit_card_number', $cardNumber)->first();
    }

    public function updateBalance(int $userId, float $amount): bool
    {
        $user = User::find($userId);
        if ($user) {
            $user->balance += $amount;
            return $user->save();
        }
        return false;
    }
}