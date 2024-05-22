<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Driver;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Driver;

class DriverListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'drivers';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [

            TD::make('name', __('Fullname'))
                ->render(fn (Driver $driver) => Link::make($driver->name . ' ' . $driver->surname)
                    ->route('platform.screens.driver.edit', $driver->id)),

            TD::make('phone', __('Phone')),

            TD::make('email', __('Email')),

            TD::make('taxi', __('Taxi'))
                ->render(fn (Driver $driver) => view('label_marker', ['label' => $driver->taxi->brand . ' ' . $driver->taxi->model, 'color' => $driver->taxi->color])),

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
                ->render(fn (Driver $driver) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

                        Link::make(__('Edit'))
                            ->route('platform.screens.driver.edit', $driver->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Confirm delete a driver?'))
                            ->method('remove', [
                                'id' => $driver->id,
                            ]),
                    ])),
        ];
    }
}
