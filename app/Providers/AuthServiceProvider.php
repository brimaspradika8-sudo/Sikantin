<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\BankAccount;
use App\Policies\OrderPolicy;
use App\Policies\BankAccountPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Order::class => OrderPolicy::class,
        BankAccount::class => BankAccountPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
