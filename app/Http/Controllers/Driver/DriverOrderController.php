<?php

namespace App\Http\Controllers\Client;

use App\Enums\OrderStatus;
use App\Events\UpdateClientOrderEvent;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Order;

class DriverOrderController extends Controller
{
    /**
     * Get order by id
     */
    public function getOrders($request): JsonResponse
    {
        try {
            return response()->json(['data' => Order::where('driver_id', $request->user->id)->get(), 'message' => 'Order accepted!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get New Orders
     */
    public function getNewOrders($request): JsonResponse
    {
        try {
            $orders = Order::new()->where('car_class_id', $request->user->car_class_id)->get();
            return response()->json(['data' => $orders, 'message' => 'Order accepted!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    /**
     * Accept Order
     */
    public function acceptOrder($request, $orderId): JsonResponse
    {
        try {
            $order = Order::find($orderId);
            if (!$order) {
                return response()->json(['data' => [], 'message' => 'Order not found!'], 404);
            } else if ($order->status !== OrderStatus::New->value) {
                return response()->json(['data' => [], 'message' => 'The order has already been confirmed by another driver!'], 500);
            }

            $driver = Driver::find($request->user->id);
            $order->driver()->associate($driver);
            $order->status = OrderStatus::Active->value;
            $order->save();

            event(new UpdateClientOrderEvent($order));

            return response()->json(['data' => $order, 'message' => 'Order accepted!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
