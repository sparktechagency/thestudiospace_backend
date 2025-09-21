<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Art\CreateArtRequest;
use App\Services\Art\CreateArtService;
use App\Services\Art\DeleteArtService;
use App\Services\Art\GetArtService;

class ArtController extends Controller
{
    protected $createArtService;
    protected $getArtService;
    protected $deleteArtService;
    public function __construct(
        CreateArtService $createArtService,
        GetArtService $getArtService,
        DeleteArtService $deleteArtService,
    ){
        $this->createArtService = $createArtService;
        $this->getArtService = $getArtService;
        $this->deleteArtService = $deleteArtService;
    }
    public function getArt()
    {
        return $this->execute(function(){
            return $this->getArtService->getArt();
        });
    }
    public function createArt(CreateArtRequest $createArtRequest)
    {
        return $this->execute(function() use ($createArtRequest){
            $data = $createArtRequest->validated();
            return $this->createArtService->createArt($data);
        });
    }
    public function deleteArt($art_id)
    {
        return $this->execute(function() use ($art_id){
            return $this->deleteArtService->deleteArt($art_id);
        });
    }
}
