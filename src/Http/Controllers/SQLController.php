<?php

namespace Nile\LaravelServer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Nile\LaravelServer\NileServerSDK;

class SQLController extends Controller
{
    public function __construct(protected NileServerSDK $sdk) {}

    public function run(Request $request, $workspace, $database)
    {
        $sql = $request->input('sql');
        $userId = $request->attributes->get('nile_user_id');
        $tenantId = $request->attributes->get('nile_tenant_id');

        return response()->json(
            $this->sdk->executeSQL($workspace, $database, $sql, $userId, $tenantId)
        );
    }
}
