<?php

namespace MCris112\FileSystemManager\Resources\FmFolder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FmFolderResource extends JsonResource
{

    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'disk' => $this->disk,
            'name' => $this->name,

            'meta' => [
                'files' => $this->files_count,
                'size' => $this->files_sum_size
            ],

            'timestamps' => [
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at
            ]
        ];
    }
}
