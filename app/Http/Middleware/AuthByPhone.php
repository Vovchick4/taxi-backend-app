<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Client;
use App\Models\Driver;
use Illuminate\Http\Request;

class AuthByPhone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the authorization token from the request header
        $rememberToken = $request->header('Authorization');

        // Format the token to remove any prefixes
        $formattedRememberToken = $this->formatHeaderToken($rememberToken);

        // Determine if the request is for a driver or a client
        $isDriver = filter_var($request->input('isDriver', false), FILTER_VALIDATE_BOOLEAN);
        $user = $isDriver ? Driver::where('remember_token', $formattedRememberToken)->first() : Client::where('remember_token', $formattedRememberToken)->first();

        if ($user) {
            // Attach the user to the request for further use
            $request->merge(['user' => $user]);
            return $next($request);
        } else {
            // If authentication fails, return a JSON response with a 403 status code
            return response()->json(['message' => 'Unauthorized: Invalid token'], 403);
        }
    }

    /**
     * Formats the authorization token from the request header.
     *
     * @param string $token
     * @return string
     */
    private function formatHeaderToken(string $token): string
    {
        // Split the token by spaces to handle prefixes
        $words = explode(" ", $token);
        return count($words) > 1 ? implode(" ", array_slice($words, 1)) : $token;
    }
}
