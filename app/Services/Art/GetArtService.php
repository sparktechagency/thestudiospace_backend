<?php

namespace App\Services\Art;

use App\Models\Art;
use App\Traits\ResponseHelper;

class GetArtService
{
   use ResponseHelper;

   public function getArt()
   {
    $art = Art::get();
    return $this->successResponse($art,"Art reterived successfully.");
   }
}
