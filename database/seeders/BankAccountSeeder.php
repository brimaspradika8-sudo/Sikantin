<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::where('role', 'seller')->first();
        if (!$seller) {
            return;
        }

        $bankAccounts = [
            [
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_holder' => $seller->name,
                'is_primary' => true,
                'is_active' => true,
            ],
            [
                'bank_name' => 'Mandiri',
                'account_number' => '0987654321',
                'account_holder' => $seller->name,
                'is_primary' => false,
                'is_active' => true,
            ],
        ];

        foreach ($bankAccounts as $account) {
            BankAccount::updateOrCreate(
                ['account_number' => $account['account_number']],
                [
                    'user_id' => $seller->id,
                    'bank_name' => $account['bank_name'],
                    'account_holder' => $account['account_holder'],
                    'is_primary' => $account['is_primary'],
                    'is_active' => $account['is_active'],
                ]
            );
        }
    }
}
