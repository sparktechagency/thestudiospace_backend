<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserEducationRequest;
use App\Http\Requests\User\UpdateUserExperienceRequest;
use App\Http\Requests\User\UpdateUserGalleryRequest;
use App\Http\Requests\User\UpdateUserInfoRequest;
use App\Http\Requests\User\UpdateUserSkillRequest;
use App\Services\User\GetUserEducationService;
use App\Services\User\GetUserExperienceService;
use App\Services\User\GetUserGalleryService;
use App\Services\User\GetUserInfoService;
use App\Services\User\GetUserSkillService;
use App\Services\User\UpdateUserEducationService;
use App\Services\User\UpdateUserExperienceService;
use App\Services\User\UpdateUserGalleryService;
use App\Services\User\UpdateUserInfoService;
use App\Services\User\UpdateUserSkillService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $getUserInfoService;
    protected $updateUserInfoService;
    protected $getUserExperienceService;
    protected $upateUserExperienceService;
    protected $getUserGalleryService;
    protected $updateUserGalleryService;
    protected $getUserEducatonService;
    protected $updateUserEducationService;

    protected $getUserSkillService;
    protected $updateUserSkillService;

    public function __construct(
        GetUserInfoService $getUserInfoService,
        UpdateUserInfoService $updateUserInfoService,
        GetUserExperienceService $getUserExperienceService,
        UpdateUserExperienceService $updateUserExperienceService,
        GetUserGalleryService $getUserGalleryService,
        UpdateUserGalleryService $updateUserGalleryService,
        GetUserEducationService $getUserEducationService,
        UpdateUserEducationService $updateUserEducationService,
        GetUserSkillService $getUserSkillService,
        UpdateUserSkillService $updateUserSkillService,
    )
    {
        $this->getUserInfoService = $getUserInfoService;
        $this->updateUserInfoService = $updateUserInfoService;
        $this->getUserExperienceService = $getUserExperienceService;
        $this->upateUserExperienceService = $updateUserExperienceService;
        $this->getUserGalleryService = $getUserGalleryService;
        $this->updateUserGalleryService = $updateUserGalleryService;
        $this->getUserEducatonService = $getUserEducationService;
        $this->updateUserEducationService = $updateUserEducationService;
        $this->getUserSkillService = $getUserSkillService;
        $this->updateUserSkillService = $updateUserSkillService;
    }
    public function getUserInfo()
    {
        return $this->execute(function (){
            return $this->getUserInfoService->getUserInfo();
        });
    }
    public function updateUserInfo(UpdateUserInfoRequest $updateUserInfoRequest)
    {
        return $this->execute(function() use ($updateUserInfoRequest){
             $data = $updateUserInfoRequest->validated();
            return $this->updateUserInfoService->updateUserInfo($data);
        });
    }
    public function getUserExperience()
    {
        return $this->execute(function (){
            return $this->getUserExperienceService->getUserExperience();
        });
    }
    public function updateUserExperience(UpdateUserExperienceRequest $updateUserExperienceRequest)
    {
        return $this->execute(function() use ($updateUserExperienceRequest){
             $data = $updateUserExperienceRequest->validated();
            return $this->upateUserExperienceService->updateUserExperience($data);
        });
    }
    public function getUserGallery()
    {
        return $this->execute(function (){
            return $this->getUserGalleryService->getUserGallery();
        });
    }
    public function updateUserGallery(UpdateUserGalleryRequest $updateUserGalleryRequest)
    {
        return $this->execute(function() use ($updateUserGalleryRequest){
             $data = $updateUserGalleryRequest->validated();
            return $this->updateUserGalleryService->updateUserGallery($data);
        });
    }
    public function getUserEducation()
    {
        return $this->execute(function (){
            return $this->getUserEducatonService->getUserEducation();
        });
    }
    public function updateUserEducation(UpdateUserEducationRequest $updateUserEducationRequest)
    {
        return $this->execute(function() use ($updateUserEducationRequest){
             $data = $updateUserEducationRequest->validated();
            return $this->updateUserEducationService->updateUserEducation($data);
        });
    }
    public function getUserSkill()
    {
        return $this->execute(function (){
            return $this->getUserSkillService->getUserSkill();
        });
    }
    public function updateUserSkill(UpdateUserSkillRequest $updateUserSkillRequest)
    {
        return $this->execute(function() use ($updateUserSkillRequest){
             $data = $updateUserSkillRequest->validated();
            return $this->updateUserSkillService->updateUserSkill($data);
        });
    }
}
