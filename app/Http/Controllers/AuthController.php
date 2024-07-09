<?php

namespace App\Http\Controllers;

use Orchid\Attachment\File;
use App\Http\Requests\RegisterRequest;
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
use Illuminate\Support\Facades\Validator;

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
     * Register user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $isDriver = filter_var($request->input('isDriver', false), FILTER_VALIDATE_BOOLEAN);

            $isExist = $isDriver ? Driver::where('phone', $validatedData['phone'])->first() : Client::where('phone', $validatedData['phone'])->first();
            if ($isExist) {
                $text =  $isDriver ? 'Driver' : 'Client';
                return response()->json(['message' => "{$text} exists with this phone!"], 401);
            }

            $createUser = $isDriver ? new Driver() : new Client();
            // Assign common data
            $createUser->phone = $validatedData['phone'];
            $createUser->city = $validatedData['city'];
            $createUser->name = $validatedData['name'];
            $createUser->surname = $validatedData['surname'];
            $createUser->email = isset($validatedData['email']) ? $validatedData['email'] : null;

            // Assign driver-specific data
            if ($isDriver) {
                $createUser->passport_expiration_date = $validatedData['passport_expiration_date'];
                if ($request->hasFile('passport_image')) {
                    $file = new File($request->file('passport_image'));
                    $attachment = $file->load();
                    $createUser->passport_image = '/storage/' . $attachment->physicalPath();
                }
            }
            $createUser->save();

            return response()->json(['message' => 'Register success!', 'data' => $isDriver ? Driver::find($createUser->id) : Client::find($createUser->id)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
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
            $isDriver = filter_var($request->input('isDriver', false), FILTER_VALIDATE_BOOLEAN);

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

            $findCode->delete();

            $findedUser = $isDriver ? Driver::where('phone', $phone)->first() : Client::where('phone', $phone)->first();

            if ($findedUser) {
                return response()->json(['message' => 'Welcome back!', 'data' => $findedUser], 200);
            }

            return response()->json(['message' => 'Create account!', 'link.to' => 'personaldata'], 200);
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
     * Update a user (Driver or Client)
     */
    public function updateUser(Request $request): JsonResponse
    {
        try {
            $user = $request->user; // Assuming you get the user object from the request
            // Determine the table based on user type
            $table = $user instanceof Driver ? 'drivers' : 'users';
            // Define validation rules based on the user's role
            $rules = [
                'city' => 'sometimes|string|max:255',
                'name' => 'sometimes|string|max:255',
                'surname' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:' . $table . ',email,' . $user->id,
                'phone' => 'sometimes|string|unique:' . $table . ',phone,' . $user->id,
                'avatar_image' => 'sometimes|image|mimes:jpg,jpeg,png,gif|max:4096', // max 4MB
            ];

            // Additional rules for Driver
            if ($user instanceof Driver) {
                $rules['passport_expiration_date'] = 'sometimes|date_format:Y-m-d';
                $rules['passport_image'] = 'sometimes|image|mimes:jpg,jpeg,png,gif|max:4096'; // max 4MB
            }

            // Validate the request data
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['message' => 'Vlidation error', 'errors' => $validator->errors()], 400);
            }

            // Update user fields based on role
            foreach ($request->all() as $key => $value) {
                if ($request->hasFile($key)) {
                    // Handle file upload
                    $file = new File($request->file($key));
                    $attachment = $file->load();
                    $user->$key = '/storage/' . $attachment->physicalPath();
                } elseif ($key !== '_token' && $key !== 'user' && $key !== 'isDriver') {
                    // Update only the fields that are not files or unnecessary parameters
                    $user->$key = $value;
                }
            }

            // Save the updated user
            $user->save();

            // Return a success response
            return response()->json(['message' => 'User updated successfully!', 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
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
                'phone' => 'required'
            ]);
            $phone = $request->input('phone');

            $potentialCode = new VerifyCode();
            $potentialCode->phone = $phone;
            $potentialCode->save();

            // Send the verification code via SMS

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

    /**
     * Send a call to the given phone number.
     *
     * @param string $phone
     */
    public function getCodes()
    {
        return VerifyCode::all();
    }
}
