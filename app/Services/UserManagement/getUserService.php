<?php

namespace App\Services\UserManagement;

use App\Models\User;
use App\Traits\ResponseHelper;

class GetUserService
{
    use ResponseHelper;

    public function getUser($request)
    {
        // Search keyword (optional)
        $keyword = $request->keyword ?? null;

        // Default pagination size
        $perPage = $request->per_page ?? 20;

        // Query: Exclude ADMIN users
        $query = User::where('role', '!=', 'ADMIN');

        // If keyword exists → Search by name, email, phone
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%")
                  ->orWhere('phone_number', 'like', "%$keyword%");
            });
        }

        // Paginate results
        $users = $query->orderBy('id', 'desc')->paginate($perPage);

        if ($users->isEmpty()) {
            return $this->successResponse([], "No users found.");
        }

        return $this->successResponse($users, "Users retrieved successfully.");
    }
}
