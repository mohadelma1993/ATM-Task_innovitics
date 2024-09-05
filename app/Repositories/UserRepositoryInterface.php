<?php

namespace App\Repositories;
use App\Models\User;

interface UserRepositoryInterface
{
    //
    public function create(array $data): User;
    public function findByCardNumber(string $cardNumber): ?User;
    public function updateBalance(int $userId, float $amount): bool;
}
