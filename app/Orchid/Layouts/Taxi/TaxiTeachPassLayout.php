<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Taxi;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\DateTimer;

class TaxiTeachPassLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            DateTimer::make('driver.tech_passport_date')
                ->title('Teach passport expiration date')
                ->required()
                ->format('Y-m-d'),

            Picture::make('driver.tech_passport_image')
                ->title('Teach passport image')
                ->storage('public')
                ->required()
                ->targetRelativeUrl()
                ->acceptedTypes('image/*') // Allow any image type
                ->maxFilesize(4), // Max file size in megabytes
        ];
    }
}
