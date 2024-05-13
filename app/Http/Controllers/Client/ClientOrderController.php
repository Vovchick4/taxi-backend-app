<?php

namespace App\Http\Controllers\Client;

use App\Models\Order;
use App\Events\TrackOrderEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCreateRequest;
use App\Models\CarClass;
use App\Models\Client;

class ClientOrderController extends Controller
{
    /**
     * Create a new order
     */
    public function create(OrderCreateRequest $request): JsonResponse
    {
        try {
            $request->validated();
            $data = $request->except(['user']);
            $client = $request->user;

            // Format start_location as a POINT using ST_GeomFromText
            $startLatitude = $data['start_location']['latitude'];
            $startLongitude = $data['start_location']['longitude'];
            $endLatitude = $data['end_location']['latitude'];
            $endLongitude = $data['end_location']['longitude'];

            // Format start_location and end_location as JSON
            $data['start_location'] = json_encode(['latitude' => $startLatitude, 'longitude' => $startLongitude]);
            $data['end_location'] = json_encode(['latitude' => $endLatitude, 'longitude' => $endLongitude]);

            $order = new Order();
            foreach ($data as $key => $value) {
                $order[$key] = $value;
            }
            $findedClient = Client::find($client->id);
            $findedCarClass = CarClass::find($data['car_class_id']);
            $order->client()->associate($findedClient);
            $order->car_class()->associate($findedCarClass);
            $order->save();

            // event(new TrackOrderEvent($order));

            return response()->json(['data' => $order, 'message' => 'Order created!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
