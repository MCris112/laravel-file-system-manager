<?php

namespace MCris112\FileSystemManager\Collections;

use Illuminate\Database\Eloquent\Collection;
use MCris112\FileSystemManager\Resources\FmFile\FmFileListResource;

class FmFileCollection extends Collection
{

    /**
     * Convert current collection into a resource to use as response
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function toResource(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return FmFileListResource::collection($this->collect());
    }
}
