<?php

namespace Nile\LaravelServer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Nile\LaravelServer\NileServerSDK;

class AuthController extends Controller
{
    public function __construct(protected NileServerSDK $sdk) {}

    public function signup(Request $request, $workspace, $database)
    {
        return response()->json($this->sdk->createUser($workspace, $database, $request->all()));
    }

    public function login(Request $request, $workspace, $database)
    {
        return response()->json($this->sdk->loginUser($workspace, $database, $request->all()));
    }

    public function me(Request $request, $workspace, $database)
    {
        return response()->json($this->sdk->getMe($workspace, $database));
    }
}
