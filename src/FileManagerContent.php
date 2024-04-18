<?php

namespace MCris112\FileSystemManager;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use MCris112\FileSystemManager\Resources\FmFile\FmFileListCollection;
use MCris112\FileSystemManager\Resources\FmFolder\FmFolderResource;

class FileManagerContent
{

    public function __construct(protected Collection|array $folders, protected LengthAwarePaginator $files)
    {
        if(!$this->folders instanceof Collection) $this->folders = collect($this->folders);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getFiles(): LengthAwarePaginator
    {
        return $this->files;
    }

    /**
     * @return Collection
     */
    public function getFolders(): Collection
    {
        return $this->folders;
    }

    public function toResource(): array
    {
        return [
            'folders' => FmFolderResource::collection($this->folders),
            'files' => new FmFileListCollection($this->files)
        ];
    }
}
