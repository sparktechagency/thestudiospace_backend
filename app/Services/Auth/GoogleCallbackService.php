<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleCallbackService
{
    use ResponseHelper;
    public function googleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]
        );
        Auth::login($user);
        $token = $user->createToken('social_token')->plainTextToken;
        return $this->successResponse([
            'message' => 'Google login successful',
            'user' => $user,
            'token' => $token
        ]);
    }
}
