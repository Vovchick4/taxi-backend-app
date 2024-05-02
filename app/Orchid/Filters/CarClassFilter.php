<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use App\Models\CarClass;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Select;
use Illuminate\Database\Eloquent\Builder;

class CarClassFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Car class');
    }

    /**
     * The array of matched parameters.
     *
     * @return array
     */
    public function parameters(): array
    {
        return ['car_class'];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereHas('car_class', function (Builder $query) {
            $query->where('slug', $this->request->get('car_class'));
        });
    }

    /**
     * Get the display fields.
     */
    public function display(): array
    {
        return [
            Select::make('car_class')
                ->fromModel(CarClass::class, 'name', 'slug')
                ->empty()
                ->value($this->request->get('car_class'))
                ->title(__('Choose a car class')),
        ];
    }

    /**
     * Value to be displayed
     */
    public function value(): string
    {
        return $this->name() . ': ' . CarClass::where('slug', $this->request->get('car_class'))->first()->name;
    }
}
