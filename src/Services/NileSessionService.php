<?php

namespace Nile\LaravelServer\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class NileSessionService
{
    public static function resolveJwtSession(Request $request): ?array
    {
        $sessionToken = $request->cookie('__Secure-nile.session-token');
        if (!$sessionToken) return null;

        try {
            $jwtSecret = config('nile.jwt_secret');
            $jwtAlg = config('nile.jwt_algorithm', 'HS256');
            $maxAge = config('nile.jwt_max_age', 3600); // en segundos

            // Decode JWT
            $decoded = JWT::decode($sessionToken, new Key($jwtSecret, $jwtAlg));
            $payload = (array) $decoded;

            // Simular callbacks.jwt(token, trigger?, session?)
            $token = self::jwtCallback($payload);

            if (!$token) return null;

            $expiresAt = Carbon::now()->addSeconds($maxAge);

            $session = [
                'user' => [
                    'name' => $token['name'] ?? null,
                    'email' => $token['email'] ?? null,
                    'image' => $token['picture'] ?? null,
                ],
                'expires' => $expiresAt->toISOString(),
            ];

            // Simular callbacks.session(session, token)
            $session = self::sessionCallback($session, $token);

            return $session;
        } catch (\Throwable $e) {
            Log::warning('[JWT_SESSION] failed: ' . $e->getMessage());
            return null;
        }
    }

    protected static function jwtCallback(array $token): array
    {
        // Aquí podrías hacer actualización del token si usas refresh tokens, etc.
        return $token;
    }

    protected static function sessionCallback(array $session, array $token): array
    {
        // Puedes modificar los datos que se exponen al cliente
        return $session;
    }
}
