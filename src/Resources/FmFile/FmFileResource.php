<?php

namespace MCris112\FileSystemManager\Resources\FmFile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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

        return [
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

                'metadata' => $this->metadata
            ],

            'variations' => $this->variations,

            'timestamps' => [
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at
            ]
        ];
    }
}
