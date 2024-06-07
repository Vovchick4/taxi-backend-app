<?php

namespace App\Models;

use App\Models\Taxi;
use Orchid\Screen\AsSource;
use Orchid\Filters\Types\Where;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Types\WhereDateStartEnd;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarClass extends Model
{
    use HasFactory, AsSource;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'tariff_name',
        'is_discount',
        'tariff_price',
        'tariff_discount_price'
    ];

    protected $casts = [
        'is_discount' => 'boolean',
        'tariff_price' => 'double',
        'tariff_discount_price' => 'double'
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id'             => Where::class,
        'name' => Where::class,
        'slug'     => Where::class,
        'created_at'     => WhereDateStartEnd::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'slug',
        'created_at',
    ];

    /**
     * Define a relationship to the Client model.
     */
    public function taxis()
    {
        return $this->hasMany(Taxi::class);
    }
}
