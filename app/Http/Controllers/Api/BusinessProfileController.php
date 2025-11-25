<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BusinessProfileManagement\GetUserService;
use App\Services\BusinessProfileManagement\BusinessFeaturesService;
use App\Services\BusinessProfileManagement\PostPermissionService;
use App\Services\BusinessProfileManagement\ViewUserService;

class BusinessProfileController extends Controller
{
    protected $getUserService;
    protected $businessFeaturesService;
    protected $postPermissionService;
    protected $viewUserService;

    public function __construct(
        GetUserService $getUserService,
        BusinessFeaturesService $businessFeaturesService,
        PostPermissionService $postPermissionService,
        ViewUserService $viewUserService
    ) {
        $this->getUserService = $getUserService;
        $this->businessFeaturesService = $businessFeaturesService;
        $this->postPermissionService = $postPermissionService;
        $this->viewUserService = $viewUserService;
    }
   public function getUser(Request $request)
    {
        return $this->execute(function()use($request){
            return $this->getUserService->getUser($request);
        });
    }
     public function viewUser($id)
    {
        return $this->execute(function()use($id){
            return $this->viewUserService->viewUser($id);
        });
    }
      public function postPermission($id)
    {
        return $this->execute(function()use($id){
            return $this->postPermissionService->postPermission($id);
        });
    }
     public function businessFeature($id)
    {
        return $this->execute(function()use($id){
            return $this->businessFeaturesService->businessFeature($id);
        });
    }

}
