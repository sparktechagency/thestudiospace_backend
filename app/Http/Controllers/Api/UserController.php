<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CoverImageUpdateRequest;
use App\Http\Requests\User\CreateUserSkillRequest;
use App\Http\Requests\User\UpdateUserEducationRequest;
use App\Http\Requests\User\UpdateUserExperienceRequest;
use App\Http\Requests\User\UpdateUserGalleryRequest;
use App\Http\Requests\User\UpdateUserInfoRequest;
use App\Services\User\AcceptConnectionService;
use App\Services\User\CancelConnectionService;
use App\Services\User\CoverImageUpdateService;
use App\Services\User\CreateUserEducationService;
use App\Services\User\CreateUserExperienceService;
use App\Services\User\CreateUserSkillService;
use App\Services\User\DeleteGalleryService;
use App\Services\User\DeleteUserSkillService;
use App\Services\User\GetUserConnectionService;
use App\Services\User\GetUserEducationService;
use App\Services\User\GetUserExperienceService;
use App\Services\User\GetUserGalleryService;
use App\Services\User\GetUserInfoService;
use App\Services\User\GetUserSkillService;
use App\Services\User\SingleEducationService;
use App\Services\User\SingleExperienceService;
use App\Services\User\UpdateUserEducationService;
use App\Services\User\UpdateUserExperienceService;
use App\Services\User\UpdateUserGalleryService;
use App\Services\User\UpdateUserInfoService;
use App\Services\User\UserConnectionService;
use App\Services\User\UserProfileCountService;
use App\Services\User\UserProfileViewService ;
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
    protected $createUserExperienceService;
    protected $createUserEducationService;
    protected $singleExperiencService;
    protected $singleEducationService;
    protected $deleteGalleryService;
    protected $acceptConnectionService;
    protected $cancelConnectionService;
    protected $coverImageUpdateService;
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
        UserProfileViewService $userProfileViewService,
        UserProfileCountService $userProfileCountService,
        CreateUserExperienceService $createUserExperienceService,
        CreateUserEducationService $createUserEducationService,
        SingleExperienceService $singleExperienceService,
        SingleEducationService $singleEducationService,
        DeleteGalleryService $deleteGalleryService,
        AcceptConnectionService $acceptConnectionService,
        CancelConnectionService $cancelConnectionService,
        CoverImageUpdateService $coverImageUpdateService,
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
        $this->createUserExperienceService = $createUserExperienceService;
        $this->createUserEducationService = $createUserEducationService;
        $this->singleExperiencService = $singleExperienceService;
        $this->singleEducationService = $singleEducationService;
        $this->deleteGalleryService = $deleteGalleryService;
        $this->acceptConnectionService = $acceptConnectionService;
        $this->cancelConnectionService = $cancelConnectionService;
        $this->coverImageUpdateService = $coverImageUpdateService;
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
    public function updateUserInfoCover(CoverImageUpdateRequest $coverImageUpdateRequest)
    {
        return $this->execute(function() use ($coverImageUpdateRequest){
             $data = $coverImageUpdateRequest->validated();
            return $this->coverImageUpdateService->updateUserInfoCover($data);
        });
    }
    public function getUserExperience()
    {
        return $this->execute(function (){
            return $this->getUserExperienceService->getUserExperience();
        });
    }
    public function singleExperience($experience_id)
    {
        return $this->execute(function () use ($experience_id){
            return $this->singleExperiencService->singleExperience($experience_id);
        });
    }
    public function createUserExperience(UpdateUserExperienceRequest $updateUserExperienceRequest)
    {
       return $this->execute(function() use ($updateUserExperienceRequest){
             $data = $updateUserExperienceRequest->validated();
            return $this->createUserExperienceService->createUserExperience($data);
        });
    }
    public function updateUserExperience(UpdateUserExperienceRequest $updateUserExperienceRequest,$experience_id)
    {
        return $this->execute(function() use ($updateUserExperienceRequest,$experience_id){
             $data = $updateUserExperienceRequest->validated();
            return $this->upateUserExperienceService->updateUserExperience($data,$experience_id);
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
    public function deleteGallery($gallery_id)
    {
        return $this->execute(function()use ($gallery_id){
            return $this->deleteGalleryService->deleteGallery($gallery_id);
        });
    }
    public function getUserEducation()
    {
        return $this->execute(function (){
            return $this->getUserEducatonService->getUserEducation();
        });
    }
      public function singleEducation($education_id)
    {
        return $this->execute(function () use($education_id){
            return $this->singleEducationService->singleEducation($education_id);
        });
    }
    public function createUserEducation(UpdateUserEducationRequest $updateUserEducationRequest)
    {
        return $this->execute(function() use ($updateUserEducationRequest){
             $data = $updateUserEducationRequest->validated();
            return $this->createUserEducationService->createUserEducation($data);
        });
    }
    public function updateUserEducation(UpdateUserEducationRequest $updateUserEducationRequest, $education_id)
    {
        return $this->execute(function() use ($updateUserEducationRequest,$education_id){
             $data = $updateUserEducationRequest->validated();
            return $this->updateUserEducationService->updateUserEducation($data,$education_id);
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
    public function acceptUserConnection($connection_id)
    {
        return $this->execute(function() use ($connection_id){
            return $this->acceptConnectionService->acceptUserConnection($connection_id);
        });
    }
    public function cancelUserConnection($connection_id)
    {
        return $this->execute(function() use ($connection_id){
            return $this->cancelConnectionService->cancelUserConnection($connection_id);
        });
    }
}
