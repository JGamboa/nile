<?php

namespace Nile\LaravelServer\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OAuthCallbackController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $url = $request->fullUrl();
            $provider = collect(explode('/', $request->path()))->last();

            $baseProxyUrl = config('nile.proxy_routes.callback');
            $targetUrl = $baseProxyUrl . '/' . $provider;

            if ($request->getQueryString()) {
                $targetUrl .= '?' . $request->getQueryString();
            }

            $res = Http::withHeaders($request->headers->all())
                ->send($request->method(), $targetUrl, [
                    'body' => $request->getContent(),
                ]);

            if ($res->status() === 302 && $res->header('Location')) {
                return response($res->body(), 302)->withHeaders($res->headers());
            }

            return response($res->body(), $res->status())->withHeaders($res->headers());
        } catch (\Throwable $e) {
            Log::error('[OAUTH CALLBACK] ' . $e->getMessage());
            return response('An unexpected error has occurred.', 400);
        }
    }
}
