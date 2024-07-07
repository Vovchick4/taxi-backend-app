<?php

namespace App\Models;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Enums\ClientRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;

class Driver extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use AsSource;
    use Attachable;

    protected $fillable = [
        'city',
        'name',
        'surname',
        'email',
        'phone',
        'role',
        'passport_expiration_date',
        'passport_image',
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
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'name',
        'created_at',
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

    // Method to get the full path for passport_image
    public function getPassportImageUrlAttribute()
    {
        if ($this->passport_image) {
            return public_path('storage') . $this->passport_image;
        }
        return null;
    }
}
