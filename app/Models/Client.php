<?php

namespace App\Models;

use App\Enums\ClientRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'password',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'verify_code',
    ];

    protected $casts = [
        'role' => ClientRole::class,
        'verified_at' => 'datetime',
        'verify_date' => 'datetime',
    ];

    /**
     * Define a relationship to the Order model.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
