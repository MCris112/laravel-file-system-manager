<?php

namespace MCris112\FileSystemManager\Base;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use MCris112\FileSystemManager\Exceptions\DiskDriverIsNotSupported;

class FileSystemManagerBase
{
    protected FilesystemAdapter $adapter;

    public function __construct(protected string $disk)
    {

        if(in_array($this->disk, config('filesystemmanager.storage.size') ?? [])) throw new DiskDriverIsNotSupported($this->disk);
        $this->adapter = Storage::disk($this->disk);
    }
}
