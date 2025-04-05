<?php

namespace Nile\LaravelServer\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    public function show(Request $request)
    {
        return $this->proxy($request, 'GET');
    }

    public function destroy(Request $request)
    {
        return $this->proxy($request, 'DELETE');
    }

    protected function proxy(Request $request, string $method)
    {
        try {
            $url = config('nile.proxy_routes.session'); // ej: https://auth.nile.dev/session

            $response = Http::withHeaders($request->headers->all())
                ->send($method, $url, [
                    'body' => $request->getContent(),
                ]);

            return response($response->body(), $response->status())->withHeaders($response->headers());
        } catch (\Throwable $e) {
            Log::error("[SESSION $method] " . $e->getMessage());
            return response()->json(['error' => 'Session proxy failed'], 500);
        }
    }
}
