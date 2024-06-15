<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(User $user, EmailVerificationRequest $request): RedirectResponse|JsonResponse
    {

        //$user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(
                config('app.frontend_url') . RouteServiceProvider::HOME . '?email_verified=1'
            );
            
            //return response()->json(['status' => 'already-verified']);
        }
        
        if (! hash_equals((string)  $request->route('hash') , sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(
            config('app.frontend_url') . RouteServiceProvider::HOME . '?email_verified=1'
        );

        //return response()->json(['status' => 'verified']);
    }
}
