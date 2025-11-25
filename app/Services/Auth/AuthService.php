<?php

namespace App\Services\Auth;

use App\Mail\otpMail;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class AuthService
{
    use ResponseHelper;
    public function register(array $data)
    {
        $user = User::create($data);
        if($user){
            $user->update([
                'fcm_token' => $data['fcm_token'] ?? null
            ]);
        }
        $otp = rand(100000, 999999);
        $user['otp'] =$otp;
        Redis::setex('otp_' . $user->id, 600, $otp);
        $opt_info= [
            'otp'=> $otp,
            'name'=> $user->name,
        ];
        Mail::to($user->email)->queue(new otpMail($opt_info));

        $admins = User::where('role', 'ADMIN')->get();
        $notificationData = [
            'title' => 'New User Registered',
            'message' => $user->name . ' has just registered.',
            'type' => $user->user_type ?? 'USER'
        ];
        $notificationService = new NotificationService();
        $notificationService->send($admins, $notificationData);
        return $this->successResponse($user,"Registered successfully, check your email for OTP.");
    }
}
