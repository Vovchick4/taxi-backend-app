<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Taxi;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\DateTimer;

class TaxiInsurancePolicyLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            DateTimer::make('driver.insurance_policy_date')
                ->title('Taxi insurance policy expiration date')
                ->required()
                ->format('Y-m-d'),

            Picture::make('driver.insurance_policy_image')
                ->title('Insurance policy image')
                ->storage('public')
                ->required()
                ->targetRelativeUrl()
                ->acceptedTypes('image/*') // Allow any image type
                ->maxFilesize(4), // Max file size in megabytes
        ];
    }
}
