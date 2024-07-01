<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
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
     * Boot the model and register model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Register the 'creating' event
        static::creating(function ($model) {
            // Set the created_at timestamp to the current time in a specific timezone using Carbon
            $timezone = config('app.timezone'); // Retrieve the timezone from your Laravel configuration
            $model->created_at = Carbon::now($timezone);
            $model->updated_at = Carbon::now($timezone);
            $model->remember_token = Str::random(60);
        });
    }

    /**
     * Define a relationship to the Order model.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
