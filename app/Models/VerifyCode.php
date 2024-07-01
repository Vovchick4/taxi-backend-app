<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerifyCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'phone',
        'code',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    /**
     * Boot the model and register model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Register the 'saving' event
        static::saving(function ($model) {
            // Automatically generate a 4-digit code
            $model->code = rand(100000, 999999);

            // Set the created_at timestamp to the current time in a specific timezone using Carbon
            $timezone = config('app.timezone'); // Retrieve the timezone from your Laravel configuration
            $model->created_at = Carbon::now($timezone);
        });
    }
}
