<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse|JsonResponse
    {

        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(
                config('app.frontend_url') . RouteServiceProvider::HOME . '?email_verified=1'
            );
            
            //return response()->json(['status' => 'already-verified']);
        }

        if (!URL::hasValidSignature($request)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }


        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link'], 403);
        }
        
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(
            config('app.frontend_url') . RouteServiceProvider::HOME . '?email_verified=1'
        );

        //return response()->json(['status' => 'verified']);
    }
}
