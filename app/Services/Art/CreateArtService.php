<?php

namespace App\Services\Art;

use App\Models\Art;
use App\Traits\ResponseHelper;

class CreateArtService
{
   use ResponseHelper;
   public function createArt($data)
   {
      $art = Art::create($data);
      return $this->successResponse($art,"Art created Successfully.");
   }
}
