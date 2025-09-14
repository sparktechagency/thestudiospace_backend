<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResendOtpRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Services\Auth\AuthService;
use App\Services\Auth\GoogleCallbackService;
use App\Services\Auth\LoginService;
use App\Services\Auth\LogoutService;
use App\Services\Auth\ResendOtpService;
use App\Services\Auth\ResetPasswordService;
use App\Services\Auth\UpdateProfileService;
use App\Services\Auth\UserProfileService;
use App\Services\Auth\VerifyOtpService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $authService;
    protected $verifyOtpService;
    protected $resendOtpService;
    protected $loginService;
    protected $userProfileService;
    protected $updateProfileService;
    protected $resetPasswordService;
    protected $logoutService;
    protected $googleCallbackService;
   public function __construct(
        AuthService $authService,
        VerifyOtpService $verifyOtpService,
        ResendOtpService $resendOtpService,
        LoginService $loginService,
        UserProfileService $userProfileService,
        UpdateProfileService $updateProfileService,
        ResetPasswordService $resetPasswordService,
        LogoutService $logoutService,
        GoogleCallbackService $googleCallbackService,
    )
    {
        $this->authService = $authService;
        $this->verifyOtpService = $verifyOtpService;
        $this->resendOtpService = $resendOtpService;
        $this->loginService = $loginService;
        $this->userProfileService = $userProfileService;
        $this->updateProfileService = $updateProfileService;
        $this->resetPasswordService = $resetPasswordService;
        $this->logoutService = $logoutService;
        $this->googleCallbackService = $googleCallbackService;
    }
   public function register(RegisterRequest $register)
    {
        return $this->execute(function () use ($register) {
            $data = $register->validated();
            return $this->authService->register($data);
        });
    }
    public function verifyOtp(VerifyOtpRequest $verifyOtpRequest)
    {
        return $this->execute(function () use ($verifyOtpRequest) {
            $data = $verifyOtpRequest->validated();
            return $this->verifyOtpService->verifyOtp($data);
        });
    }
    public function resendOtp(ResendOtpRequest $resendOtpRequest)
    {
        return $this->execute(function () use ($resendOtpRequest) {
            $data = $resendOtpRequest->validated();
            return $this->resendOtpService->resendOtp($data);
        });
    }
    public function login(LoginRequest $loginRequest)
    {
        return $this->execute(function() use ($loginRequest){
            $data= $loginRequest->validated();
            return $this->loginService->login($data);
        });
    }
    public function getProfile()
    {
        return $this->execute(function(){
           return $this->userProfileService->getProfile();
        });
    }
    public function updateProfile(UpdateProfileRequest $updateProfileRequest)
    {
        return $this->execute(function() use ($updateProfileRequest){
            $data = $updateProfileRequest->validated();
            return $this->updateProfileService->updateProfile($data);
        });
    }
    public function resetPassword(ResetPasswordRequest $resetPasswordRequest)
    {
        return $this->execute(function() use ($resetPasswordRequest){
            $data = $resetPasswordRequest->validated();
            return $this->resetPasswordService->resetPassword($data);
        });
    }
    public function logout()
    {
        return $this->execute(function(){
            return $this->logoutService->logout();
        });
    }
    public function googleLogin()
    {
        return $this->execute(function (){
             return Socialite::driver('google')->redirect();
        });
    }
    public function googleCallback()
    {
        return $this->execute(function (){
            return $this->googleCallbackService->googleCallback();
        });
    }
}
