<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Taxi;

use Orchid\Screen\Field;
use App\Models\Driver;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Relation;

class TaxiDriverLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Relation::make('taxi.driver')
                ->fromModel(Driver::class, 'name')
                ->displayAppend('full')
                ->title('Choose driver'),
        ];
    }
}
