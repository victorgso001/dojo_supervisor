<?php

namespace App\Http\Middleware;

use Closure;
use App\Admin;

class Auth
{
    public function handle($request, Closure $next)
    {
        if ($request->header('token') == '') {
            return response([
                'error_code' => 'empty_token',
            ], 401);
        }

        $admin = Admin::where('token', $request->header('token'))->first();

        if (!$admin) {
            return response([
                'error_code' => 'not_authenticated',
                'message' => 'Admin not authenticated',
            ], 401);
        }

        $request->admin = $admin;
        return $next($request);
    }
}
