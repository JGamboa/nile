<?php

declare(strict_types=1);

namespace Nile\LaravelServer;

use Illuminate\Support\Facades\Http;

final class NileServerSDK
{
    private $token;

    public function __construct()
    {
        $this->token = config('nile.token');
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function createUser(string $workspace, string $database, array $data)
    {
        return $this->client()->post("/workspaces/{$workspace}/databases/{$database}/users", $data)->json();
    }

    public function loginUser(string $workspace, string $database, array $data)
    {
        return $this->client()->post("/workspaces/{$workspace}/databases/{$database}/users/login", $data)->json();
    }

    public function getMe(string $workspace, string $database)
    {
        return $this->client()->get("/workspaces/{$workspace}/databases/{$database}/users/me")->json();
    }

    public function createTenant(string $workspace, string $database, array $data)
    {
        return $this->client()->post("/workspaces/{$workspace}/databases/{$database}/tenants", $data)->json();
    }

    public function listTenants(string $workspace, string $database)
    {
        return $this->client()->get("/workspaces/{$workspace}/databases/{$database}/tenants")->json();
    }

    public function executeSQL(string $workspace, string $database, string $sql, string $userId, string $tenantId)
    {
        return $this->client()->post("/workspaces/{$workspace}/databases/{$database}/sql", [
            'sql' => $sql,
            'user_id' => $userId,
            'tenant_id' => $tenantId,
        ])->json();
    }

    private function client()
    {
        return Http::withToken($this->token)
            ->baseUrl(config('nile.base_url'));
    }
}
