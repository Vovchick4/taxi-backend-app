<?php

namespace App\Orchid\Layouts\Taxi;

use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;
use App\Orchid\Filters\CarClassFilter;

class TaxiFiltersLayout extends Selection
{
    /**
     * @return string[]|Filter[]
     */
    public function filters(): array
    {
        return [
            CarClassFilter::class,
        ];
    }
}
