<?php

namespace MCris112\FileSystemManager\Traits;

trait FmPagination
{

    public function paginate(): array
    {
        return [
            'total' => $this->total(),
            'perPage' => $this->perPage(),
            'currentPage' => $this->currentPage(),
            'totalPages' => $this->lastPage()
        ];
    }
}
