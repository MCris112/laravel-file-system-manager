<?php

namespace MCris112\FileSystemManager\Manager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use MCris112\FileSystemManager\Base\AbstractManager;
use MCris112\FileSystemManager\Enums\FmFileSize;
use MCris112\FileSystemManager\Exceptions\FmFileNotFoundException;
use MCris112\FileSystemManager\Exceptions\NotEnoughStorageException;
use MCris112\FileSystemManager\Facades\FileSystemManager;
use MCris112\FileSystemManager\FmFileContent;
use MCris112\FileSystemManager\Models\FmFolder;
use MCris112\FileSystemManager\Models\FmFile;
use Throwable;

class FileManager extends AbstractManager
{

    public function __construct(string $disk, FmFile|int|null $parent = null)
    {
        parent::__construct($disk, $parent);
    }

    public function parent(FmFile|int|null $parent): static
    {
        return parent::parent($parent);
    }

    /**
     * @param $name
     * @param int|null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search($name, ?int $perPage = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return FmFile::whereDisk($this->disk)->whereIsParent()->where( function($q)use($name) {
            return $q->where('name', 'LIKE', '%'.$name.'%')->orWhere('path_filename', 'LIKE', '%'.str($name)->slug().'%' );
        })->paginate();
    }

    /**
     * Find a file by id or full path (folder + filename)
     * @param int|string $idOrPath
     * @return FmFile
     * @throws FmFileNotFoundException
     */
    public function find(int|string $idOrPath): FmFile
    {
        return \Cache::rememberForever('fm_file_find_'.str($idOrPath)->slug(), function() use ($idOrPath){
            $query = FmFile::with([
                'metadataInt', 'metadataVarchar', 'metadataDateTime', 'metadataDecimal'
            ]);

            if(is_string($idOrPath))
            {
                $query->whereDisk($this->disk)->where( \DB::raw('concat(path_folder, path_filename)'), $idOrPath);
            }else{
                $query->whereId($idOrPath);
            }

            /** @var FmFile|null $model */
            $model = $query->first();

            if(!$model) throw new FmFileNotFoundException;

            return $model;
        });
    }

    /**
     * Get Files used size based on disk
     * @return int
     */
    public function used(): int
    {
        return \Cache::rememberForever('fm_storage_files_used',
                fn() => FmFile::whereDisk($this->disk)->sum('size')
            );
    }


    /**
     * Save the file information
     * @param UploadedFile|string $file
     * @param FmFileSize|string $size This can be a base64 encoded
     * @param bool $isPublic
     * @param int $createdBy
     * @param string $folder
     * @param ?string $name If name is not set, It will be the filename
     * @param \Closure|null $doAfterSaveFile Do something after save function( FmFileContent $fileContent, FmFile $model)
     * @return FmFile
     * @throws NotEnoughStorageException
     * @throws Throwable
     */
    public function save(UploadedFile|string $file, FmFileSize|string $size, bool $isPublic, int $createdBy, string $folder, ?string $name, ?\Closure $doAfterSaveFile = null): FmFile
    {
        $fileContent = new FmFileContent($file, $folder, $name, $this->disk);

        if($fileContent->getSize() > FileSystemManager::disk($this->disk)->left()) throw new NotEnoughStorageException;

        // Save the file and get the model
        $model = Db::transaction( function () use ($isPublic, $fileContent, $file, $createdBy, $size) {
            $model = FmFile::saveAsModel(
                $fileContent->getName(),
                $fileContent->getDisk(),
                $fileContent->getFolder(),
                $fileContent->getFilename(),
                $fileContent->getSize(),
                $size,
                $fileContent->getFileType(),
                $fileContent->getMimeType(),
                $fileContent->getExtension(),
                $isPublic,
                $createdBy,
                $fileContent->getMetadata(),
                $this->parent?->getKey()
            );

            $fileContent->getFile()->storeAs($fileContent->getFullPath(), [
                'disk' => $this->disk,
            ]);

            return $model;
        });

        //If function exists, do something with the data given
        if($doAfterSaveFile)
            call_user_func($doAfterSaveFile, $fileContent, $model );

        return $model;
    }


    function delete(): void
    {
        if(!$this->parent) throw new \InvalidArgumentException("The file must be set");

        DB::transaction(function (){
           $this->parent->delete();
        });
    }
}
