<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Taxi;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;
use App\Models\Taxi;

class TaxiListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'taxi';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('brand', __('Brand'))
                ->filter(Input::make())
                ->sort(),

            TD::make('model', __('Model'))
                ->filter(Input::make())
                ->sort(),

            // TD::make('driver', __('Driver info'))
            //     ->render(fn (Taxi $taxi) => $taxi->driver->name . ' ' . $taxi->driver->surname . ',<br/>' . $taxi->driver->phone),

            TD::make('VIN_code', __('VIN Code'))
                ->filter(Input::make())
                ->sort(),

            TD::make('color', __('Color'))
                ->render(fn ($taxi) => '<i style="color: ' . $taxi->color . ';font-size:24px;">●</i>')
                ->sort(),

            TD::make('car_class', __('Car class'))
                ->render(fn ($taxi) =>  $taxi->car_class->name),

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
                ->render(fn (Taxi $taxi) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

                        Link::make(__('Edit'))
                            ->route('platform.screens.taxi.edit', $taxi->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Confirm delete car class?'))
                            ->method('remove', [
                                'id' => $taxi->id,
                            ]),
                    ])),
        ];
    }
}
