<?php

namespace MCris112\FileSystemManager\Manager;

use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use MCris112\FileSystemManager\Base\AbstractManager;
use MCris112\FileSystemManager\Exceptions\FmFolderContentException;
use MCris112\FileSystemManager\Exceptions\FmFolderNotFoundException;
use MCris112\FileSystemManager\FileManagerContent;
use MCris112\FileSystemManager\Models\FmFile;
use MCris112\FileSystemManager\Models\FmFolder;

class FolderManager extends AbstractManager
{
    public function __construct(string $disk, FmFolder|int|null $parent = null)
    {
        parent::__construct($disk, $parent);
    }

    public function parent(FmFolder|int|null $parent): static
    {
        return parent::parent($parent);
    }

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

    /**
     * Search for folders
     * @param string $name
     * @param int|null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search(string $name = "", ?int $perPage = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return FmFolder::whereDisk($this->disk)->where('name', 'LIKE', '%'.$name.'%')
            ->withMetadata()
            ->paginate($perPage);
    }

    /**
     * @param int $id
     * @return FmFolder
     */
    public function find(int $id): FmFolder
    {
        return \Cache::rememberForever('fm_file_find_'.$id, function() use ($id){
            /** @var FmFolder|null $folder */
            $folder = FmFolder::whereDisk($this->disk)->whereId($id)->withMetadata()->first();


            if(!$folder) throw new FmFolderNotFoundException;

            return $folder;
        });
    }

    /**
     * Create a new folder, and if parent exists, create a sub folder
     * @param string $name
     * @return FmFolder
     */
    public function create(string $name): FmFolder
    {
        $data = [
            'disk' => $this->disk,
            'name' => $name
        ];

        if($this->parent) return $this->parent->folders()->create($data);

        return FmFolder::create($data);

    }


    /**
     * Put some content inside of Folder
     * @param FmFolder|FmFile|array $content
     * @return void
     */
    public function put(FmFolder|FmFile|Array $content): void
    {
        if(!$this->parent) throw new InvalidArgumentException("Folder must be set");

        /** @var FmFolder $folder */
        $folder = $this->parent;

        $items = [];
        if(!is_array($content)) $items = [$content];

        foreach ($items as $item)
        {
            if($item instanceof FmFolder) $folder->folders()->save($item);

            if($item instanceof FmFile) $folder->files()->save($item);
        }
    }

    /**
     * Delete all the folder or just delete a specific content inside
     * @param FmFolder|FmFile|null $model If ypu let this as null, try to delete all folders
     * @throws FmFolderContentException|\Throwable
     */
    public function delete(FmFolder|FmFile|array|null $model = null, bool $deleteContent = false): void
    {
        \DB::transaction(function() use ($model, $deleteContent){
            if(!$this->parent) throw new InvalidArgumentException("Folder must be set");
            /** @var FmFolder $folder */
            $folder = $this->parent;

            $items = [];

            if($model)
            {
                if(!is_array($model)) $items = [];
                if(is_array($model)) $items = $model;

                foreach ($items as $item)
                $item->delete();
                return;
            }

            if(!$deleteContent)
                if($folder->folders()->exists() || $folder->files()->exists()) throw new FmFolderContentException();

            $folder->folders()->delete();
            $folder->files()->delete();
        });
    }
}
