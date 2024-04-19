<?php

namespace MCris112\FileSystemManager\Enums;

use MCris112\FileSystemManager\FmTypeSize;

enum FmFileSize: string
{
    case THUMBNAIL = 'thumbnail';

    case FULL = 'full';

    case SQUARE = 'square';

    case VIDEO = 'video';

    public function getSize(): FmTypeSize
    {
        return match($this)
        {
            self::THUMBNAIL => new FmTypeSize( config('filesystemmanager.sizes.thumbnail.width'), config('filesystemmanager.sizes.thumbnail.height') ),
            self::SQUARE => new FmTypeSize( config('filesystemmanager.sizes.square.width'), config('filesystemmanager.sizes.square.height') ),
            self::VIDEO => new FmTypeSize( config('filesystemmanager.sizes.video.width'), config('filesystemmanager.sizes.video.height') ),
        };
    }
}
