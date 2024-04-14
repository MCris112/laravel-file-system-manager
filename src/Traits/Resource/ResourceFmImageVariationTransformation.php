<?php

namespace MCris112\FileSystemManager\Traits\Resource;

use MCris112\FileSystemManager\Models\FmFile;
use MCris112\FileSystemManager\Resources\FmFile\FmFileListResource;

trait ResourceFmImageVariationTransformation
{

    protected string $fmImageResource = FmFileListResource::class;

    public function fmImageVariation(): array
    {
        $data = [];
        /** @var FmFile $image */
        foreach($this->fmimage as $image)
        {
            $data[$image->pivot->field] = new $this->fmImageResource($image);
        }

        return $data;
    }
}
