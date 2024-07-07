<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Driver;

use App\Models\Taxi;
use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\DateTimer;

class DriverPassportDataLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            DateTimer::make('driver.passport_expiration_date')
                ->title('Driver Passport expiration date')
                ->required()
                ->format('Y-m-d'),

            Picture::make('driver.passport_image')
                ->title('Passport Image')
                ->storage('public')
                ->required()
                ->targetRelativeUrl()
                ->acceptedTypes('image/*') // Allow any image type
                ->maxFilesize(4), // Max file size in megabytes
        ];
    }
}
