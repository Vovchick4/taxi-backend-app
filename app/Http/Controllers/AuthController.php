<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Support\Str;
use App\Models\Client;
use App\Models\VerifyCode;
use App\Http\Requests\SendCodeRequest;
use App\Http\Requests\VerifyCodeRequest;
use App\Models\Driver;
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
    public function sendCodeOrCall(SendCodeRequest $request): JsonResponse
    {
        try {
            $request->validated();
            $phone = $request->input('phone');
            $isDriver = filter_var($request->input('isDriver', false), FILTER_VALIDATE_BOOLEAN);

            if ($isDriver) {
                $driver = Driver::where('phone', $phone)->first();
                if ($driver->exists) {
                    // Send Call Approve
                    $this->sendCall($phone);
                    return response()->json(['message' => 'Call sended!', 'data' => ['phone' => $phone, 'link.to' => 'verifycall']], 200);
                } else {
                    return response()->json(['message' => 'Driver with this phone not found!'], 404);
                }
            }

            $client = Client::where('phone', $phone)->first();
            if ($client->exists) {
                // Send Call Approve
                $this->sendCall($phone);
                return response()->json(['message' => 'Call sended!', 'data' => ['phone' => $phone, 'link.to' => 'verifycall']], 200);
            }

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

            return response()->json(['message' => 'Send code to the phone!', 'data' => ['phone' => $phone, 'link.to' => 'verifycode']], 200);
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

    public function verifyCall(Request $request)
    {
        try {
            $phone = $request->input('phone');
            $isDriver = filter_var($request->input('isDriver', false), FILTER_VALIDATE_BOOLEAN);

            $user = $isDriver ? Driver::where('phone', $phone)->first() : Client::where('phone', $phone)->first();
            if ($user) {
                if ($user->approved) {
                    $user->approved = false;
                    $user->save();
                    return response()->json(['data' => $user, 'message' => "User approved!"], 200);
                } else {
                    return response()->json(['message' => "User don't approved!"], 401);
                }
            } else {
                return response()->json(['message' => 'User not found!'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function approveCall(Request $request)
    {
        try {
            $phone = $request->input('To');  // Get the phone number to identify the user
            $digits = $request->input('Digits');

            if ($digits == '1') {
                // User approved
                // Find the user by phone number
                $user = Driver::where('phone', $phone)->first();
                if (!$user) {
                    $user = Client::where('phone', $phone)->first();
                }

                // If the user is found, update their approved status
                if ($user) {
                    $user->approved = true;  // Assuming you have an `approved` field in your model
                    $user->save();

                    return response()->json(['data' => $user, 'message' => 'User approved!'], 200);
                } else {
                    return response()->json(['message' => 'User not found!'], 404);
                }
            } else {
                return response()->json(['message' => 'User did not approve the call.'], 200);
            }
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

    /**
     * Logout user
     */
    public function logoutUser(Request $request): JsonResponse
    {
        $request->user->approved = false;
        $request->user->remember_token = Str::random(60);
        $request->user->save();
        return response()->json(['message' => 'Logout success!', 'data' => []], 200);
    }

    public function resendCode(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|phone'
            ]);
            $phone = $request->input('phone');

            $user = Client::where('phone', $phone)->first();

            if (!$user) {
                return response()->json(['message' => 'User with this phone not found!'], 404);
            }

            $potentialCode = new VerifyCode();
            $potentialCode->phone = $phone;
            $potentialCode->save();

            return response()->json(['message' => 'Send code to the phone!', 'data' => ['phone' => $phone, 'link.to' => 'verifycode']], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function resendCall(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|phone'
            ]);

            $phone = $request->input('phone');
            $isDriver = filter_var($request->input('isDriver', false), FILTER_VALIDATE_BOOLEAN);

            $user = $isDriver ? Driver::where('phone', $phone)->first() : Client::where('phone', $phone)->first();

            if (!$user) {
                return response()->json(['message' => 'User with this phone not found!'], 404);
            }

            // Initiate the call using Twilio
            $this->sendCall($phone);

            return response()->json(['message' => 'Verification call sent!', 'data' => ['phone' => $phone, 'link.to' => 'verifycall']], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Send a call to the given phone number.
     *
     * @param string $phone
     */
    protected function sendCall(string $phone): void
    {
        $twimlUrl = config('services.twilio.twiml_url'); // URL to your TwiML instructions

        $this->twilio->calls->create(
            $phone, // To
            config('services.twilio.from'), // From
            [
                'url' => $twimlUrl
            ]
        );
    }
}
