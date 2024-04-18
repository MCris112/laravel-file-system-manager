<?php

namespace MCris112\FileSystemManager\Manager;

use Illuminate\Database\Eloquent\Model;
use MCris112\FileSystemManager\Base\AbstractManager;
use MCris112\FileSystemManager\Exceptions\FmFolderNotFoundException;
use MCris112\FileSystemManager\FileManagerContent;
use MCris112\FileSystemManager\Models\FmFile;
use MCris112\FileSystemManager\Models\FmFolder;

class FolderManager extends AbstractManager
{

    /**
     * Get the parents of files and folder to show it into the file manager interface
     * @param int $id
     * @param int|null $perPage
     * @return FileManagerContent
     */
    public function content(int $id, ?int $perPage = null): FileManagerContent
    {
        return new FileManagerContent(
            FmFolder::whereParentId($id)->whereDisk($this->disk)->withMetadata()->get(),
            FmFile::whereDisk($this->disk)->paginate($perPage)
        );
    }

    public function search(string $name = "", ?int $perPage = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return FmFolder::whereDisk($this->disk)->where('name', 'LIKE', '%'.$name.'%')
            ->withMetadata()
            ->paginate($perPage);
    }

    public function find(int $id): FmFolder
    {
        return \Cache::rememberForever('fm_file_find_'.$id, function() use ($id){
            /** @var FmFolder|null $folder */
            $folder = FmFolder::whereDisk($this->disk)->whereId($id)->withMetadata()->first();


            if(!$folder) throw new FmFolderNotFoundException;

            return $folder;
        });
    }
}
