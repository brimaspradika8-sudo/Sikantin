<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
        protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'store_name', 'address', 'status', 'is_closed'
    ];

        protected $guard_name = 'web';

    public static function inferRoleFromEmail(string $email): string
    {
        $email = strtolower($email);

        if (str_ends_with($email, '@admin.com')) {
            return 'admin';
        }

        if (str_ends_with($email, '@seller.com')) {
            return 'seller';
        }

        return 'user';
    }

    public function applyEmailDomainRole(): void
    {
        $expectedRole = self::inferRoleFromEmail($this->email);

        if ($expectedRole === 'user' && $this->role !== 'user') {
            return;
        }

        if ($this->role === $expectedRole) {
            return;
        }

        $this->role = $expectedRole;

        if ($expectedRole === 'admin' || $expectedRole === 'seller') {
            $this->status = 'active';
        }

        $this->save();

        if (method_exists($this, 'syncRoles')) {
            $this->syncRoles([$expectedRole]);
        }
    }

    public function products() { return $this->hasMany(Product::class, 'user_id'); }
    public function orders() { return $this->hasMany(Order::class, 'user_id'); }
    public function sales() { return $this->hasMany(Order::class, 'seller_id'); }
    public function sellerApplication() { return $this->hasOne(SellerApplication::class); }
    public function appliedAsSeller() { return $this->hasMany(SellerApplication::class, 'seller_user_id'); }
    public function bankAccounts() { return $this->hasMany(BankAccount::class); }
    public function notifications() { return $this->hasMany(Notification::class); }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
