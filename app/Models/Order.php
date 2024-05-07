<?php

namespace App\Models;

use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'status',
        'payment_status',
        'payment_method',
        'client_id',
    ];

    protected $casts = [
        /* ... */
        'status' => OrderStatus::class,
        'payment_status' => OrderPaymentStatus::class,
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
}
