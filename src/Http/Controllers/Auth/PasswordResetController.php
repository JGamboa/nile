<?php

namespace Nile\LaravelServer\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PasswordResetController extends Controller
{
    public function request(Request $request)
    {
        try {
            $url = config('nile.proxy_routes.password_reset'); // ex: https://auth.nile.dev/password-reset

            $response = Http::withHeaders($request->headers->all())
                ->send($request->method(), $url, [
                    'body' => $request->getContent(),
                ]);

            // Manejar redirección
            if ($response->status() === 302 && $response->header('Location')) {
                return redirect()->away($response->header('Location'));
            }

            return response($response->body(), $response->status())->withHeaders($response->headers());
        } catch (\Throwable $e) {
            Log::error('[PASSWORD RESET] ' . $e->getMessage());
            return response()->json(['error' => 'Password reset proxy failed'], 500);
        }
    }

    public function confirm(Request $request)
    {
        return $this->request($request); // En caso de usar POST confirm también
    }
}
