<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CarClass;

use App\Models\Taxi;
use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Relation;

class CarClassRelationTaxiLayout extends Rows
{

    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [

            Relation::make('carClass.taxis')
                ->fromModel(Taxi::class, 'model')
                ->multiple()
                ->title('Choose taxi'),
        ];
    }
}
