<?php

namespace App\Models;

use App\Models\Driver;
use App\Models\CarClass;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Types\WhereDateStartEnd;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Taxi extends Model
{
    use HasFactory, AsSource, Filterable;

    // Specify the custom table name
    protected $table = 'taxi';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'model',
        'brand',
        'VIN_code',
        'color',
        'car_class_id'
    ];

    protected $casts = [
        /* ... */
        'car_class_id' => 'integer',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id'             => Where::class,
        'model'         => Like::class,
        'brand' => Like::class,
        'VIN_code'     => Where::class,
        'created_at'     => WhereDateStartEnd::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'created_at',
    ];

    /**
     * Define a relationship to the CarClass model.
     */
    public function car_class()
    {
        return $this->belongsTo(CarClass::class);
    }

    /**
     * Define a relationship to the Driver model.
     */
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    /**
     * @return string
     */
    public function getFullAttribute(): string
    {
        return $this->attributes['brand'] . ' (' . $this->attributes['model'] . ')' . ' ' . $this->attributes['VIN_code'];
    }
}
