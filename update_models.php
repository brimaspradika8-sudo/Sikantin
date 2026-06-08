<?php

$dir = __DIR__ . '/app/Models/';

$updates = [
    'User.php' => <<<'EOT'
    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'store_name', 'address', 'status'
    ];

    public function products() { return $this->hasMany(Product::class, 'user_id'); }
    public function orders() { return $this->hasMany(Order::class, 'user_id'); }
    public function sales() { return $this->hasMany(Order::class, 'seller_id'); }
EOT,
    'Category.php' => <<<'EOT'
    protected $fillable = ['name', 'slug', 'icon'];
    public function products() { return $this->hasMany(Product::class); }
EOT,
    'Product.php' => <<<'EOT'
    protected $fillable = ['user_id', 'category_id', 'name', 'slug', 'description', 'price', 'stock', 'image'];
    public function seller() { return $this->belongsTo(User::class, 'user_id'); }
    public function category() { return $this->belongsTo(Category::class); }
EOT,
    'Cart.php' => <<<'EOT'
    protected $fillable = ['user_id'];
    public function items() { return $this->hasMany(CartItem::class); }
EOT,
    'CartItem.php' => <<<'EOT'
    protected $fillable = ['cart_id', 'product_id', 'quantity'];
    public function product() { return $this->belongsTo(Product::class); }
EOT,
    'Order.php' => <<<'EOT'
    protected $fillable = ['user_id', 'seller_id', 'order_number', 'total_amount', 'status', 'payment_method'];
    public function items() { return $this->hasMany(OrderItem::class); }
    public function customer() { return $this->belongsTo(User::class, 'user_id'); }
    public function seller() { return $this->belongsTo(User::class, 'seller_id'); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function pickupQrcode() { return $this->hasOne(PickupQrcode::class); }
EOT,
    'OrderItem.php' => <<<'EOT'
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];
    public function product() { return $this->belongsTo(Product::class); }
EOT,
    'Payment.php' => <<<'EOT'
    protected $fillable = ['order_id', 'amount', 'payment_status', 'payment_proof'];
    public function order() { return $this->belongsTo(Order::class); }
EOT,
    'PickupQrcode.php' => <<<'EOT'
    protected $fillable = ['order_id', 'token', 'expires_at', 'is_used'];
    protected $casts = ['expires_at' => 'datetime', 'is_used' => 'boolean'];
    public function order() { return $this->belongsTo(Order::class); }
EOT,
];

foreach ($updates as $file => $content) {
    if ($file === 'User.php') {
        $path = $dir . $file;
        $file_content = file_get_contents($path);
        // Replace fillable
        $file_content = preg_replace('/protected \$fillable = \[[^\]]*\];/', $content, $file_content);
        file_put_contents($path, $file_content);
    } else {
        $path = $dir . $file;
        $file_content = file_get_contents($path);
        $file_content = str_replace('use HasFactory;', "use HasFactory;\n\n" . $content, $file_content);
        file_put_contents($path, $file_content);
    }
}
echo "Models updated successfully.\n";
