<?php

use App\Models\Client;
use App\Models\Driver;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Client Broadcasting
Broadcast::channel('client-track-order.{orderId}', function (Client $client, int $orderId) {
    return (int) $client->id === (int) $client->orders()->find($orderId)->client_id;
});

// Driver Broadcasting
Broadcast::channel('driver-track-new-order.{carClassId}', function (Driver $driver, int $carClassId) {
    return (int) $carClassId === (int) $driver->taxi()->get()->car_class_id;
});
