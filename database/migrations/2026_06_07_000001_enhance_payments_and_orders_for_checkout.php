<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('customer_note')->nullable()->after('payment_method');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('total_amount');
            $table->decimal('tax_amount', 12, 2)->default(0)->after('discount_amount');
            $table->decimal('service_fee', 12, 2)->default(0)->after('tax_amount');
            $table->timestamp('estimated_ready_at')->nullable()->after('payment_method');
            $table->timestamp('pickup_window_at')->nullable()->after('estimated_ready_at');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_channel')->nullable()->after('payment_status');
            $table->string('transaction_id')->nullable()->after('payment_channel');
            $table->string('snap_token')->nullable()->after('transaction_id');
            $table->string('invoice_number')->nullable()->unique()->after('snap_token');
            $table->string('bank_name')->nullable()->after('payment_proof');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('account_holder')->nullable()->after('account_number');
            $table->json('raw_response')->nullable()->after('account_holder');
            $table->timestamp('paid_at')->nullable()->after('raw_response');
            $table->timestamp('verified_at')->nullable()->after('paid_at');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'payment_channel',
                'transaction_id',
                'snap_token',
                'invoice_number',
                'bank_name',
                'account_number',
                'account_holder',
                'raw_response',
                'paid_at',
                'verified_at',
                'verified_by',
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_note',
                'discount_amount',
                'tax_amount',
                'service_fee',
                'estimated_ready_at',
                'pickup_window_at',
            ]);
        });
    }
};
