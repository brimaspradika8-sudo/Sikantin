<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || $user->id === $order->seller_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'user';
    }

    public function update(User $user, Order $order): bool
    {
        return $user->id === $order->seller_id;
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->id === $order->seller_id && $order->status === 'pending_payment';
    }

    public function restore(User $user, Order $order): bool
    {
        return false;
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return false;
    }
}
