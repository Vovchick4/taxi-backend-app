<?php

namespace App\Http\Controllers\Client;

use App\Helpers\CalculateCarClassPrices;
use Carbon\Carbon;
use App\Models\CarClass;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetClientCarClassesRequest;

class ClientCarClassesController extends Controller
{
    /**
     * Get for client car classes
     */
    public function show(GetClientCarClassesRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $carClasses = CarClass::all();

            foreach ($carClasses as $key => $carClass) {
                $carClass['total_price'] = CalculateCarClassPrices::getPrice($carClass->tariff_price, $data['distance']);
            }

            return response()->json(['data' => $carClasses, 'message' => 'OK'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
