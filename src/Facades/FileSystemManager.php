<?php

namespace MCris112\FileSystemManager\Facades;

use Illuminate\Support\Facades\Facade;
use MCris112\FileSystemManager\Collections\FmFileCollection;
use MCris112\FileSystemManager\FileSystemManagerService;
use MCris112\FileSystemManager\Models\FmFile;

/**
 * @method static int size() Get the current used size
 * @method static FmFileCollection list(array $search = []) Get list of paginated files
 * @method static FileSystemManagerService disk(string $name) Set the disk adapter to use
 * @method static FmFile find(int|string $idOrPath) Find a file by id or full path (folder + filename)
 */
class FileSystemManager extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'fileSystemManager';
    }
}
