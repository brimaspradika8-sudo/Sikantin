<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add is_open field to products table (false = sold out/habis)
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_open')->default(true)->after('stock');
        });

        // Add is_closed field to users table (true = store closed/tutup)
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_closed')->default(false)->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_open');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_closed');
        });
    }
};
