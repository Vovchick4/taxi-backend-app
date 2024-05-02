<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Taxi;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class TaxiColorLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('taxi.color')
                ->type('color'),
        ];
    }
}
