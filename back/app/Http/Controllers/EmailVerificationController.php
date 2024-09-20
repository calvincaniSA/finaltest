<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    public function verify($id, $hash)
    {
        $user = \App\Models\User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => 'Email verified successfully.'], 200);
    }
}
