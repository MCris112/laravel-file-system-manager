<?php
namespace MCris112\FileSystemManager\Resources\FmFile;

use Illuminate\Http\Resources\Json\JsonResource;

class FmFileListResource extends JsonResource
{

    public function toArray(\Illuminate\Http\Request $request)
    {
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
                'url' => config('app.url').'/storage/'.$this->disk.'/'.$this->path_folder.$this->path_filename
            ],

            'timestamps' => [
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at
            ]
        ];
    }
}
