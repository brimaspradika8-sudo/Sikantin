<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->after('user_id')->constrained('vendors')->nullOnDelete();
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'vendor_id')) {
                $table->foreignId('vendor_id')->nullable()->after('seller_id')->constrained('vendors')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vendor_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'vendor_id')) {
                $table->dropConstrainedForeignId('vendor_id');
            }
        });
    }
};
