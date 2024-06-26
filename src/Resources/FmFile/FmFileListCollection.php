<?php
namespace MCris112\FileSystemManager\Resources\FmFile;

use Illuminate\Http\Resources\Json\ResourceCollection;
use MCris112\FileSystemManager\Traits\FmPagination;

class FmFileListCollection extends ResourceCollection
{
    use FmPagination;

    public function toArray(\Illuminate\Http\Request $request)
    {
        return [
            'data' => $this->collection,
            'pagination' => $this->paginate()
        ];
    }
}
