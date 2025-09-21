<?php

namespace App\Services\Art;

use App\Models\Art;
use App\Traits\ResponseHelper;

class DeleteArtService
{
    use ResponseHelper;

   public function deleteArt($art_id)
    {
        $art = Art::find($art_id);
        if (!$art) {
            return $this->errorResponse("Art not found.");
        }
        $art->delete();
        return $this->successResponse([],"Art deleted successfully.");
    }
}
