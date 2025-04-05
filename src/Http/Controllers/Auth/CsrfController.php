<?php

namespace Nile\LaravelServer\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CsrfController extends Controller
{
    public function token(Request $request)
    {
        try {
            $url = config('nile.proxy_routes.csrf');

            $response = Http::withHeaders($request->headers->all())
                ->send($request->method(), $url, [
                    'body' => $request->getContent(),
                ]);

            return response($response->body(), $response->status())->withHeaders($response->headers());
        } catch (\Throwable $e) {
            Log::error('[CSRF PROXY ERROR] ' . $e->getMessage());
            return response()->json(['error' => 'Proxy failed'], 500);
        }
    }
}
