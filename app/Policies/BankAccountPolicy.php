<?php

namespace App\Policies;

use App\Models\BankAccount;
use App\Models\User;

class BankAccountPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, BankAccount $bankAccount): bool
    {
        return $user->id === $bankAccount->user_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'seller';
    }

    public function update(User $user, BankAccount $bankAccount): bool
    {
        return $user->id === $bankAccount->user_id;
    }

    public function delete(User $user, BankAccount $bankAccount): bool
    {
        return $user->id === $bankAccount->user_id;
    }

    public function restore(User $user, BankAccount $bankAccount): bool
    {
        return false;
    }

    public function forceDelete(User $user, BankAccount $bankAccount): bool
    {
        return false;
    }
}
