<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Broadcast;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class BroadcastController extends Controller
{
    /**
     * Authenticate the request for channel access.
     */
    public function authenticate(Request $request)
    {
        if ($request->hasSession()) {
            $request->session()->reflash();
        }
        $result = Broadcast::auth($request);
        return $result;
    }

    /**
     * Authenticate the current user.
     *
     * See: https://pusher.com/docs/channels/server_api/authenticating-users/#user-authentication.
     *
     * @param Request $request
     * @return array
     */
    public function authenticateUser(Request $request): array
    {
        if ($request->hasSession()) {
            $request->session()->reflash();
        }
        $result = Broadcast::resolveAuthenticatedUser($request)
            ?? throw new AccessDeniedHttpException;
        return $result;
    }
}
