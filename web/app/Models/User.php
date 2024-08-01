<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserAccount;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'dob',
        'email',
        'password',
        'address_1',
        'address_2',
        'town',
        'country',
        'post_code',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * The attributes are attached along with other attribute
     *
     * @var array<string, string, int>
     */
    protected $appends = [
        'name', 'full_address', 'is_admin', 'account'
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
            'two_factor_expires_at' => 'datetime',
        ];
    }


    /**
     * Get the user's full name.
     */
    protected function getNameAttribute(): string
    {
        return ucwords("{$this->first_name} {$this->last_name}");
    }

    /**
     * Get the user's full address.
     */
    protected function getFullAddressAttribute(): string
    {
        $address = $this->address_1 ? $this->address_1 . ", " : "";
        $address .= $this->address_2 ? $this->address_2 . ", " : "";
        $address .= $this->town ? $this->town . ", " : "";
        $address .= $this->country ? $this->country . ", " : "";
        $address .= $this->post_code ? $this->post_code : "";
        return $address;
    }

    /**
     * Get the user's full address.
     */
    protected function getIsAdminAttribute(): bool
    {
        return $this->id === 1;
    }

    public function generateTwoFactorCode(): void
    {
        $this->timestamps = false;  // Prevent updating the 'updated_at' column
        $this->two_factor_code = rand(100000, 999999);  // Generate a random code
        $this->two_factor_expires_at = now()->addMinutes(10);  // Set expiration time
        $this->save();
    }

    public function resetTwoFactorCode(): void
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }


    public function getAccountAttribute()
    {
        return $this->account()->first();
    }

    public function account(): HasOne
    {
        return $this->hasOne(UserAccount::class);
    }

}
