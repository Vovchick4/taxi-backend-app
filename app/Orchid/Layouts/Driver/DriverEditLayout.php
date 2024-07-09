<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Driver;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Picture;

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
            Picture::make('driver.avatar_image')
                ->title('Avatar Image')
                ->storage('public')
                ->targetRelativeUrl()
                ->acceptedTypes('image/*') // Allow any image type
                ->maxFilesize(4), // Max file size in megabytes

            Input::make('driver.city')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Driver city'))
                ->placeholder(__('city')),

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
                ->title(__('Driver email'))
                ->placeholder(__('email')),
        ];
    }
}
