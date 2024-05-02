<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Taxi;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class TaxiEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('taxi.model')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Model'))
                ->placeholder(__('Model')),

            Input::make('taxi.brand')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Brand'))
                ->placeholder(__('Brand')),

            Input::make('taxi.VIN_code')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('VIN Code'))
                ->placeholder(__('VIN Code')),

        ];
    }
}
