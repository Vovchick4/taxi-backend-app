<?php

namespace App\Models;

use Carbon\Carbon;
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
        'password',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'verify_code',
    ];

    protected $casts = [
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
