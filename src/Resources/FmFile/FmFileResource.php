<?php

namespace MCris112\FileSystemManager\Resources\FmFile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MCris112\FileSystemManager\Enums\FmFileSize;
use MCris112\FileSystemManager\Models\FmFile;
use MCris112\FileSystemManager\Models\Metadata\FmMetadataInt;

class FmFileResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $metadata = [];

        /** @var FmMetadataInt $item */
        foreach ($this->metadata as $item)
        {
            $metadata[$item->attr] = $item->value;
        }

        $variations = [];



        $data = [
            'id' => $this->id,
            'name' => $this->name,

            'type' => $this->type,

            'attr' => [
                'size' => $this->size,
                'mimetype' => $this->mimetype,
                'ext' => $this->extension,
                'disk' => $this->disk,
                'path' => [
                    'filename' => $this->path_filename,
                    'folder' => $this->path_folder
                ],
                'url' => config('app.url').'/storage/'.$this->disk.'/'.$this->path_folder.$this->path_filename,

                'metadata' => $metadata
            ],

            'timestamps' => [
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at
            ]
        ];

        if($this->size_type == FmFileSize::FULL->value)
        {
            $data['variations'] = [];
            /** @var FmFile $variation */
            foreach ($this->variations as $variation)
                $data['variations'][$variation->size_type] = new FmFileResource($variation);
        }

        return $data;
    }
}
