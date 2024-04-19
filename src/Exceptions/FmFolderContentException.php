<?php

namespace MCris112\FileSystemManager\Exceptions;

class FmFolderContentException extends \Exception
{

    public function __construct()
    {
        parent::__construct( "The folder has to be empty content to be deleted", 500);
    }
}
