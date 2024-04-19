<?php

namespace MCris112\FileSystemManager\Enums;

use MCris112\FileSystemManager\FmTypeSize;
use PHPUnit\Event\InvalidArgumentException;

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

    public static function fromConfig(string $name): FmTypeSize
    {

        $config = config('filesystemmanager.sizes.'.$name);
        if (!$config) throw new InvalidArgumentException($name." is not defined");

        return new FmTypeSize( $config["width"], $config["height"] );
    }
}
