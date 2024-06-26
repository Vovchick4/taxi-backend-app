<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Driver;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class DriverEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('driver.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Driver name'))
                ->placeholder(__('name')),

            Input::make('driver.surname')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Driver surname'))
                ->placeholder(__('surname')),

            Input::make('driver.phone')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Driver phone'))
                ->placeholder(__('phone')),

            Input::make('driver.email')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Driver email'))
                ->placeholder(__('email')),
        ];
    }
}
