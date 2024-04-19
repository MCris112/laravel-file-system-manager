<?php

namespace MCris112\FileSystemManager\Exceptions;

class NotEnoughStorageException extends \Exception
{

    public function __construct()
    {
        parent::__construct("Not enough storage for saving the file", 500);
    }
}
