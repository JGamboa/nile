<?php

namespace Nile\LaravelServer\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        try {
            $method = $request->method();
            $baseUrl = config('nile.proxy_routes.signout'); // ej: https://auth.nile.dev/signout

            $provider = null;
            if ($method === 'POST') {
                $segments = explode('/', trim($request->path(), '/'));
                $provider = end($segments);
                if ($provider !== 'signout') {
                    $baseUrl .= '/' . $provider;
                }
            }

            $response = Http::withHeaders($request->headers->all())
                ->send($method, $baseUrl, [
                    'body' => $request->getContent(),
                ]);

            return response($response->body(), $response->status())->withHeaders($response->headers());
        } catch (\Throwable $e) {
            Log::error('[SIGNOUT ERROR] ' . $e->getMessage());
            return response()->json(['error' => 'Sign-out proxy failed'], 500);
        }
    }
}
