<?php

namespace App\Services\Group;

use App\Models\GroupPostSave;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class SavedUnsavedService
{
    use ResponseHelper;

    public function savedUnsaved($group_id, $group_post_id)
    {
        $user_id = Auth::id();

        // Check if record exists
        $save = GroupPostSave::where('group_id', $group_id)
            ->where('group_post_id', $group_post_id)
            ->where('user_id', $user_id)
            ->first();

        // If exists → toggle status
        if ($save) {
            $save->status = !$save->status;
            $save->save();

            $message = $save->status ? 'Post saved successfully.' : 'Post unsaved successfully.';
            return $this->successResponse($save, $message);
        }

        // If not exists → create new save
        $newSave = GroupPostSave::create([
            'group_id' => $group_id,
            'group_post_id' => $group_post_id,
            'user_id' => $user_id,
            'status' => true,
        ]);

        return $this->successResponse($newSave, 'Post saved successfully.');
    }
}
