<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserManagement\GetUserService;
use App\Services\UserManagement\BanUserService;
use App\Services\UserManagement\DeleteUserService;
use App\Services\UserManagement\ViewUserService;


class UserManagementController extends Controller
{
    protected $getUserService;
    protected $banUserService;
    protected $deleteUserService;
    protected $viewUserService;

    public function __construct(
        GetUserService $getUserService,
        BanUserService $banUserService,
        DeleteUserService $deleteUserService,
        ViewUserService $viewUserService
    ) {
        $this->getUserService = $getUserService;
        $this->banUserService = $banUserService;
        $this->deleteUserService = $deleteUserService;
        $this->viewUserService = $viewUserService;
    }
    public function getUser(Request $request)
    {
        return $this->execute(function()use($request){
            return $this->getUserService->getUser($request);
        });
    }
    public function banUser($id)
    {
        return $this->execute(function()use($id){
            return $this->banUserService->banUser($id);
        });
    }
    public function viewUser($id)
    {
        return $this->execute(function()use($id){
            return $this->viewUserService->viewUser($id);
        });
    }
    public function deleteUser($id)
    {
        return $this->execute(function()use($id){
            return $this->deleteUserService->deleteUser($id);
        });
    }
}
