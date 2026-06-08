<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // order_created, order_processing, order_ready, order_completed, payment_verified, etc
            $table->foreignId('order_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('icon')->nullable(); // icon class for frontend
            $table->string('color')->nullable(); // color class for frontend
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
