<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CarClass;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class CarClassTariffEditLayout extends Rows
{

    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('carClass.tariff_name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Tariff Name')),

            Input::make('carClass.tariff_price')
                ->type('number')
                ->max(255)
                ->required()
                ->title(__('Tariff Price (UAH)'))
                ->help(__('Tariff price by 1 km.')),

            CheckBox::make('carClass.is_discount')
                ->sendTrueOrFalse()
                ->title(__('Active discount')),

            Input::make('carClass.tariff_discount_price')
                ->type('number')
                ->value(0)
                ->max(255)
                ->title(__('Tariff Discount (UAH)'))
                ->help(__('Tariff Discount by 1 km.')),

        ];
    }
}
