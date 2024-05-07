<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Driver;

use App\Models\Taxi;
use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Relation;

class DriverTaxiRelationLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Relation::make('driver.taxi')
                ->fromModel(Taxi::class, 'model')
                ->displayAppend('full')
                ->title('Choose a taxi'),
        ];
    }
}
