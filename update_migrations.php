<?php

$dir = __DIR__ . '/database/migrations/';

$updates = [
    '0001_01_01_000000_create_users_table.php' => <<<'EOT'
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('user'); // admin, seller, user, supervisor
            $table->string('phone')->nullable();
            $table->string('store_name')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->default('active'); // active, pending, rejected
            $table->rememberToken();
            $table->timestamps();
EOT,
    'create_categories_table.php' => <<<'EOT'
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->timestamps();
EOT,
    'create_products_table.php' => <<<'EOT'
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // penjual
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
EOT,
    'create_carts_table.php' => <<<'EOT'
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
EOT,
    'create_cart_items_table.php' => <<<'EOT'
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->timestamps();
EOT,
    'create_orders_table.php' => <<<'EOT'
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending_payment', 'paid', 'processing', 'ready', 'completed', 'cancelled'])->default('pending_payment');
            $table->string('payment_method')->nullable();
            $table->timestamps();
EOT,
    'create_order_items_table.php' => <<<'EOT'
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
EOT,
    'create_payments_table.php' => <<<'EOT'
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('payment_proof')->nullable();
            $table->timestamps();
EOT,
    'create_pickup_qrcodes_table.php' => <<<'EOT'
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('token')->unique();
            $table->dateTime('expires_at');
            $table->boolean('is_used')->default(false);
            $table->timestamps();
EOT
];

$files = scandir($dir);
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;
    
    $content = file_get_contents($dir . $file);
    $replaced = false;
    
    foreach ($updates as $key => $replacement) {
        if (str_contains($file, $key)) {
            if ($key === '0001_01_01_000000_create_users_table.php') {
                $target = <<<'EOT'
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
EOT;
                $content = str_replace($target, $replacement, $content);
            } else {
                $target = <<<'EOT'
            $table->id();
            $table->timestamps();
EOT;
                $content = str_replace($target, $replacement, $content);
            }
            file_put_contents($dir . $file, $content);
            echo "Updated $file\n";
            $replaced = true;
            break;
        }
    }
}
