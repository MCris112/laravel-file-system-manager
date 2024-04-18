<?php

namespace MCris112\FileSystemManager\Facades;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Facade;
use MCris112\FileSystemManager\Collections\FmFileCollection;
use MCris112\FileSystemManager\FileManagerContent;
use MCris112\FileSystemManager\FileSystemManagerService;
use MCris112\FileSystemManager\Manager\AvatarFileSystemManager;
use MCris112\FileSystemManager\Manager\ContentManager;
use MCris112\FileSystemManager\Manager\FileManager;
use MCris112\FileSystemManager\Manager\FolderManager;
use MCris112\FileSystemManager\Models\FmFile;

/**
 * @method static string url(FmFile|string $modelOrPath) Get the url of the file
 * @method static int size() Get the current used size
 * @method static FileSystemManagerService disk(string $name) Set the disk adapter to use
 * @method static FmFile save(UploadedFile $file, bool $isPublic, int $createdBy, string $folder, ?string $name, ?\Closure $doAfterSaveFile = null) Save the file information and content - Do something after save function( FmFileContent $fileContent, FmFile $model)
 * @method static FileManagerContent content(?int $load = null) Get this to show it into your file manager interface
 * @method static FolderManager folder() Access to Folder Manager
 * @method static FileManager file() Access to File Manager
 * @method static AvatarFileSystemManager avatar() Access to Avatar Manager
 */
class FileSystemManager extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'fileSystemManager';
    }
}
