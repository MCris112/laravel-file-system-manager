<?php

namespace MCris112\FileSystemManager\Exceptions;

class FmFolderNotFoundException extends \Exception
{

    public function __construct()
    {
        parent::__construct('The folder you are looking for doesnt exist', 404);
    }
}
