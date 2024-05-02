<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Taxi;

use Orchid\Screen\Field;
use App\Models\CarClass;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Relation;

class TaxiRelationClassLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Relation::make('taxi.car_class')
                ->fromModel(CarClass::class, 'name')
                ->title('Choose taxi class'),
        ];
    }
}
