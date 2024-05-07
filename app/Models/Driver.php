<?php

namespace App\Models;

use App\Enums\ClientRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class Driver extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use AsSource;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'role',
        'remember_token',
    ];

    protected $hidden = [];

    protected $casts = [
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'role' => ClientRole::class,
    ];

    /**
     * Define a relationship to the Order model.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Define a relationship to the Taxi model.
     */
    public function taxi()
    {
        return $this->belongsTo(Taxi::class);
    }

    /**
     * @return string
     */
    public function getFullAttribute(): string
    {
        return $this->attributes['name'] . ' (' . $this->attributes['surname'] . ')' . ' ' . $this->attributes['phone'];
    }
}
