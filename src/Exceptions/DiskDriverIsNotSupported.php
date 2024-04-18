<?php

namespace MCris112\FileSystemManager\Exceptions;

class DiskDriverIsNotSupported extends \Exception
{

    public function __construct(string $disk)
    {
        parent::__construct("[".$disk."] is not supported", 500);
    }
}
