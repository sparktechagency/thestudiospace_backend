<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserSkillRequest;
use App\Http\Requests\User\UpdateUserEducationRequest;
use App\Http\Requests\User\UpdateUserExperienceRequest;
use App\Http\Requests\User\UpdateUserGalleryRequest;
use App\Http\Requests\User\UpdateUserInfoRequest;
use App\Services\User\CreateUserSkillService;
use App\Services\User\DeleteUserSkillService;
use App\Services\User\GetUserConnectionService;
use App\Services\User\GetUserEducationService;
use App\Services\User\GetUserExperienceService;
use App\Services\User\GetUserGalleryService;
use App\Services\User\GetUserInfoService;
use App\Services\User\GetUserSkillService;
use App\Services\User\UpdateUserEducationService;
use App\Services\User\UpdateUserExperienceService;
use App\Services\User\UpdateUserGalleryService;
use App\Services\User\UpdateUserInfoService;
use App\Services\User\UserConnectionService;
use App\Services\User\UserProfileCountService;
use App\Services\User\UserProfileViewService as UserUserProfileViewService;
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
    protected $createUserSkillService;
    protected $deleteUserSkillService;
    protected $getUserConnectionService;
    protected $userConnectionService;
    protected $userProfileViewService;
    protected $userProfileCountService;
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
        CreateUserSkillService $createUserSkillService,
        DeleteUserSkillService $deleteUserSkillService,
        GetUserConnectionService $getUserConnectionService,
        UserConnectionService $userConnectionService,
        UserUserProfileViewService $userProfileViewService,
        UserProfileCountService $userProfileCountService,
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
        $this->createUserSkillService = $createUserSkillService;
        $this->deleteUserSkillService = $deleteUserSkillService;
        $this->getUserConnectionService = $getUserConnectionService;
        $this->userConnectionService = $userConnectionService;
        $this->userProfileViewService = $userProfileViewService;
        $this->userProfileCountService = $userProfileCountService;
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
    public function createUserSkill(CreateUserSkillRequest $createUserSkillRequest)
    {
        return $this->execute(function() use ($createUserSkillRequest){
             $data = $createUserSkillRequest->validated();
            return $this->createUserSkillService->createUserSkill($data);
        });
    }
    public function deleteUserSkill($skill_id)
    {
        return $this->execute(function () use ($skill_id) {
            return $this->deleteUserSkillService->deleteUserSkill($skill_id);
        });
    }
    public function getUserConnection()
    {
        return $this->execute(function() {
            return $this->getUserConnectionService->getUserConnection();
        });
    }
    public function userConnection($connection_id)
    {
        return $this->execute(function() use ($connection_id){
            return $this->userConnectionService->userConnection($connection_id);
        });
    }
    public function userProfileView($user_id)
    {
        return $this->execute(function() use ($user_id){
            return $this->userProfileViewService->userProfielView($user_id);
        });
    }
    public function userProfileCount()
    {
        return $this->execute(function(){
           return $this->userProfileCountService->userProfileCount();
        });
    }
}
