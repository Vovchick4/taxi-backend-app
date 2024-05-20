<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Screen\AsSource;

class Order extends Model
{
    use HasFactory, AsSource;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'status',
        'payment_status',
        'payment_method',
        'client_id',
        'car_class_id',
        'start_street_name',
        'end_street_name',
        'start_location',
        'end_location',
        'total_price',
        'distance',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        /* ... */
        'status' => OrderStatus::class,
        'payment_status' => OrderPaymentStatus::class,
        'client_id' => 'integer',
        'car_class_id' => 'integer',
        'total_price' => 'double',
        'distance' => 'double',
        'start_location' => 'array',
        'end_location' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $spatialFields = [
        'start_location',
        'end_location',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id'             => Where::class,
        'status'         => Where::class,
        'payment_status' => Where::class,
        'updated_at'     => WhereDateStartEnd::class,
        'created_at'     => WhereDateStartEnd::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'updated_at',
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
        });

        // Register the 'saving' event
        static::saving(function ($model) {
            // Set the created_at timestamp to the current time in a specific timezone using Carbon
            $timezone = config('app.timezone'); // Retrieve the timezone from your Laravel configuration
            $model->created_at = Carbon::now($timezone);
        });

        // Register the 'updating' event
        static::updating(function ($model) {
            // Set the created_at timestamp to the current time in a specific timezone using Carbon
            $timezone = config('app.timezone'); // Retrieve the timezone from your Laravel configuration
            $model->updated_at = Carbon::now($timezone);
        });
    }

    /**
     * Define a relationship to the Client model.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Define a relationship to the Client model.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Define a relationship to the Client model.
     */
    public function car_class()
    {
        return $this->belongsTo(CarClass::class);
    }

    /**
     * Local scope to find orders by active status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        // Define the condition for an ACTIVE order
        // You can modify the condition to match your definition of an active order.
        return $query->where('status', OrderStatus::Active->value);
    }

    /**
     * Local scope to find orders by NEW status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNew($query)
    {
        // Define the condition for an active order
        // You can modify the condition to match your definition of an active order.
        return $query->where('status', OrderStatus::New->value);
    }
}
