<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Support\Str;
use App\Models\Client;
use App\Models\VerifyCode;
use App\Http\Requests\SendCodeRequest;
use App\Http\Requests\VerifyCodeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new TwilioClient(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    /**
     * Send Code. Verify user indity.
     */
    public function sendCode(SendCodeRequest $request): JsonResponse
    {
        try {
            $request->validated();
            $phone = $request->input('phone');

            $potentialCode = new VerifyCode();
            $potentialCode->phone = $phone;
            $potentialCode->save();

            // Send the verification code via SMS
            // $this->twilio->messages->create(
            //     $potentialCode->phone, // To
            //     [
            //         'from' => config('services.twilio.from'), // From
            //         'body' => "Your verification code is: {$potentialCode->code}"
            //     ]
            // );

            return response()->json(['message' => 'Send code to the phone!', 'data' => ['phone' => $phone]], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Send Code. Verify user indity.
     */
    public function verifyCode(VerifyCodeRequest $request): JsonResponse
    {
        try {
            $request->validated();
            $code = $request->input('code');
            $phone = $request->input('phone');

            // Assuming $phone and $code are defined and provided as input

            // Calculate the expiration time as 2 minutes ago from the current time
            $expirationTime = Carbon::now()->subMinutes(2);

            // Query the VerifyCode model for the specified phone number and code,
            // and also check that the code was created within the last 2 minutes
            $findCode = VerifyCode::where([
                ['phone', '=', $phone],
                ['code', '=', $code],
            ])
                ->where('created_at', '>=', $expirationTime)
                ->orderByDesc('created_at')
                ->first();

            if (!$findCode) {
                return response()->json(['message' => 'This code not valid or expired!'], 422);
            }

            $findedClient = Client::where('phone', $phone)->first();

            if ($findedClient) {
                $findedClient->remember_token = Str::random(60);
                return response()->json(['message' => 'Welcome back!', 'data' => $findedClient], 200);
            }

            $createdUser = new Client();
            $createdUser->phone = $phone;
            $createdUser->remember_token = Str::random(60);
            $createdUser->save();

            return response()->json(['message' => 'Created account!', 'data' => $createdUser->get()], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Reguest a user
     */
    public function getUser(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Retrived user!', 'data' => $request->user], 200);
    }
}
