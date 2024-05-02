<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Client;

class AuthByPhone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $rememberToken = $request->header('authorization');
        $formattedRememberToken = $this->headerToken($rememberToken);
        $client = Client::where('remember_token', $formattedRememberToken)->first();

        if ($client) {
            $request->merge(['user' => $client]);
            return $next($request);
        } else {
            return response()->json(["message" => "No login defined!"], 403);
        }
    }

    public function headerToken($token)
    {
        $words = explode(" ", $token);
        if (count($words) > 1) {
            $result = implode(" ", array_slice($words, 1));
        } else {
            $result = $token;
        }
        return $result;
    }
}
