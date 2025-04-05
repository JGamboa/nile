<?php

namespace Nile\LaravelServer\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        try {
            $url = config('nile.proxy_routes.verify_request'); // ej: https://auth.nile.dev/verify-request

            $response = Http::withHeaders($request->headers->all())
                ->send($request->method(), $url, [
                    'body' => $request->getContent(),
                ]);

            return response($response->body(), $response->status())->withHeaders($response->headers());
        } catch (\Throwable $e) {
            Log::error('[VERIFY REQUEST PROXY] ' . $e->getMessage());
            return response()->json(['error' => 'Verification proxy failed'], 500);
        }
    }
}
