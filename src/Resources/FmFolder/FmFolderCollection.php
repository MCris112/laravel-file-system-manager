<?php

namespace MCris112\FileSystemManager\Resources\FmFolder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use MCris112\FileSystemManager\Traits\FmPagination;

class FmFolderCollection extends ResourceCollection
{
    use FmPagination;

    public function toArray(Request $request)
    {
        return [
            'data' => $this->collection,
            'pagination' => $this->paginate()
        ];
    }
}
