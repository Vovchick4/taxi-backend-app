<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CarClass;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class CarClassEditLayout extends Rows
{

    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('carClass.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name')),

            Input::make('carClass.slug')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Slug'))
                ->placeholder(__('Slug')),

        ];
    }
}
