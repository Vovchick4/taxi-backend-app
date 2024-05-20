<?php

namespace App\Orchid\Screens\Order;

use App\Models\Order;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\Order\OrderViewLayout;
use Orchid\Screen\Sight;
use Orchid\Screen\Fields;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class OrderViewScreen extends Screen
{
    /**
     * @var Order
     */
    public $order;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Order $order): iterable
    {
        $order->load(['client', 'driver', 'car_class']);

        return [
            'order' => $order,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'View Order ' . $this->order->id;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::legend('order', [
                Sight::make('id'),
                Sight::make('status')->render(fn (Order $order) => $order->status->description()),
                Sight::make('client')->render(fn (Order $order) => $order->client->phone),
                Sight::make('payment_status')->render(fn (Order $order) => $order->payment_status->value),
            ]),
        ];
    }
}
