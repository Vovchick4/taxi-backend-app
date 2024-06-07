<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CarClass;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\CarClass;

class CarClassListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'car_classes';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [

            TD::make('name', __('Name'))
                ->render(fn (CarClass $carClass) => Link::make($carClass->name)
                    ->route('platform.screens.car_classes.edit', $carClass->id)),

            TD::make('slug', __('Slug')),

            TD::make('is_discount', __('Discount'))
                ->render(fn (CarClass $carClass) => $carClass->is_discount ? 'ON' : 'OFF'),

            TD::make('tariff_price', __('Tariff price')),

            TD::make('tariff_discount_price', __('Discount price')),

            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->sort(),

            TD::make('updated_at', __('Last edit'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (CarClass $carClass) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

                        Link::make(__('Edit'))
                            ->route('platform.screens.car_classes.edit', $carClass->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Confirm delete car class?'))
                            ->method('remove', [
                                'id' => $carClass->id,
                            ]),
                    ])),
        ];
    }
}
