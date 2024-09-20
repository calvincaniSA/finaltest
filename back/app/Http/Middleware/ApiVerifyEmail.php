<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class ApiVerifyEmail
{
    public function handle(Request $request, Closure $next, $redirectToRoute = null)
    {
        if ($request->user() instanceof MustVerifyEmailContract && !$request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Your email address is not verified.'], 403);
        }
        return $next($request);
    }
}
