<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CarClass;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Picture;

class CarClassImageLayout extends Rows
{

    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Picture::make('carClass.car_class_image')
                ->title('Image')
                ->storage('public')
                ->required()
                ->targetRelativeUrl()
                ->acceptedTypes('image/*') // Allow any image type
                ->maxFilesize(4), // Max file size in megabytes
        ];
    }
}
