<?php

namespace Nile\LaravelServer\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $method = $request->method();
            $baseUrl = config('nile.proxy_routes.signin'); // ex: https://auth.nile.dev/signin

            $provider = null;
            if ($method === 'POST') {
                $segments = explode('/', trim($request->path(), '/'));
                $provider = end($segments);
                $baseUrl .= '/' . $provider;
            }

            if ($query = $request->getQueryString()) {
                $baseUrl .= '?' . $query;
            }

            $res = Http::withHeaders($request->headers->all())
                ->send($method, $baseUrl, [
                    'body' => $request->getContent(),
                ]);

            return response($res->body(), $res->status())->withHeaders($res->headers());
        } catch (\Throwable $e) {
            Log::error('[SIGNIN ERROR] ' . $e->getMessage());
            return response()->json(['error' => 'Sign-in proxy failed'], 500);
        }
    }
}
