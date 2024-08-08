<?php

namespace App\Http\Controllers\Client;

use App\Helpers\CalculateCarClassPrices;
use App\Models\CarClass;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientCarClassesController extends Controller
{
    /**
     * Get for client car classes
     */
    public function getCarsTypes(Request $request): JsonResponse
    {
        try {
            // Check if the 'dist' and 'dur' query parameters exist
            if (!$request->has('distance') || !$request->has('duration')) {
                return response()->json(['message' => 'Missing required query parameters: dist and/or dur'], 400);
            }

            // Extracting query parameters from the URL
            $distance = floatval($request->query('distance'));
            $duration = floatval($request->query('duration'));

            $carClasses = CarClass::all();

            foreach ($carClasses as $carClass) {
                $carClass['total_price'] = CalculateCarClassPrices::getPrice($carClass->tariff_price, $distance);
            }

            return response()->json(['data' => $carClasses, 'message' => 'OK'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
