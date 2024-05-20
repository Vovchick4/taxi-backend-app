<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Order;

use App\Models\Order;
use Orchid\Screen\Sight;
use Orchid\Screen\Fields;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class OrderViewLayout extends Rows
{
    /**
     * @return Fields[]
     */
    public function fields(): array
    {
        return [
            Sight::make('id'),
            Sight::make('status')->render(fn (Order $order) => $order->status->description()),
            Sight::make('client')->render(fn (Order $order) => $order->client->phone),
            Sight::make('payment_status')->render(fn (Order $order) => $order->payment_status->description()),
        ];
    }
}
