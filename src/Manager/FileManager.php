<?php

namespace MCris112\FileSystemManager\Manager;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use MCris112\FileSystemManager\Base\AbstractManager;
use MCris112\FileSystemManager\Base\FileSystemManagerBase;
use MCris112\FileSystemManager\Exceptions\FmFileNotFoundException;
use MCris112\FileSystemManager\Models\FmFolder;
use MCris112\FileSystemManager\Models\FmFile;

class FileManager extends AbstractManager
{
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
}
