<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Taxi;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\CheckBox;

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
            CheckBox::make('taxi.is_child_chair')
                ->sendTrueOrFalse()
                ->title(__('Active child chair')),

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

            Input::make('taxi.graduation_year')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Graduation year'))
                ->placeholder(__('Graduation year')),

            Input::make('taxi.VIN_code')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('VIN Code'))
                ->placeholder(__('VIN Code')),

        ];
    }
}
