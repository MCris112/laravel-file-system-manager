<?php

namespace MCris112\FileSystemManager\Facades;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Facade;
use MCris112\FileSystemManager\Enums\FmFileSize;
use MCris112\FileSystemManager\FileManagerContent;
use MCris112\FileSystemManager\FileSystemManagerService;
use MCris112\FileSystemManager\Manager\AvatarFileSystemManager;
use MCris112\FileSystemManager\Manager\FileManager;
use MCris112\FileSystemManager\Manager\FolderManager;
use MCris112\FileSystemManager\Models\FmFile;
use MCris112\FileSystemManager\Models\FmFolder;

/**
 * @method static string url(FmFile|string $modelOrPath) Get the url of the file
 * @method static int size() Get the current size
 * @method static int used() Get the current used size
 * @method static int left() Get the current size left
 * @method static FileSystemManagerService disk(string $name) Set the disk adapter to use
 * @method static FileManagerContent content(?int $load = null) Get this to show it into your file manager interface
 * @method static FolderManager folder(FmFolder|int|null $parent = null) Access to Folder Manager
 * @method static FileManager file(FmFile|int|null $parent = null) Access to File Manager
 * @method static AvatarFileSystemManager avatar(?User $user = null) Access to Avatar Manager
 * @method static UploadedFile fromBase64(string $base64) Convert Base64 into UploadedFile
 */
class FileSystemManager extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'fileSystemManager';
    }
}
