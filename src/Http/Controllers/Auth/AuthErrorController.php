<?php

namespace Nile\LaravelServer\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthErrorController extends Controller
{
    public function show(Request $request)
    {
        try {
            $url = config('nile.proxy_routes.error'); // ej: https://auth.nile.dev/error

            $response = Http::withHeaders($request->headers->all())
                ->send($request->method(), $url, [
                    'body' => $request->getContent(),
                ]);

            return response($response->body(), $response->status())->withHeaders($response->headers());
        } catch (\Throwable $e) {
            Log::error('[AUTH ERROR PROXY] ' . $e->getMessage());
            return response()->json(['error' => 'Error proxy failed'], 500);
        }
    }
}
