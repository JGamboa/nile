<?php

namespace Nile\LaravelServer\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SignupController extends Controller
{
    public function register(Request $request)
    {
        try {
            $url = config('nile.proxy_routes.signup'); // eg: https://auth.nile.dev/signup

            $response = Http::withHeaders($request->headers->all())
                ->post($url, $request->all());

            return response($response->body(), $response->status())
                ->withHeaders($response->headers());
        } catch (\Throwable $e) {
            Log::error('[SIGNUP ERROR] ' . $e->getMessage());
            return response()->json(['error' => 'Signup failed'], 500);
        }
    }
}
