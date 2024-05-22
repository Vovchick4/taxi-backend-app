<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Order;

use App\Enums\OrderPaymentStatus;
use App\Models\Order;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OrderListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'orders';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [

            TD::make('status', __('Status'))
                ->render(fn (Order $order) => view('label_marker', ['label' => $order->status->description(), 'color' => $order->status->color()])),


            TD::make('payment_status', __('Payment Status'))
                ->render(fn (Order $order) => view('label_marker', ['label' => $order->payment_status->value, 'color' => $order->payment_status->color()])),

            TD::make('total_price', __('Total price UAH')),

            TD::make('distance', __('Distance km')),

            TD::make('start_street_name', __('Start street name')),

            TD::make('end_street_name', __('End street name')),

            TD::make('client', __('Client info'))
                ->render(fn (Order $order) => $order->client->phone),

            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Order $order) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

                        // Link::make(__('Edit'))
                        //     ->route('platform.systems.orders.edit', $order->id)
                        //     ->icon('bs.pencil'),

                        Link::make(__('View'))
                            ->route('platform.screens.order.view', $order->id)
                            ->icon('bs.eye'),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Confirm delete order?'))
                            ->method('remove', [
                                'id' => $order->id,
                            ]),
                    ])),
        ];
    }
}
