<?php

namespace MCris112\FileSystemManager\Exceptions;

class FmFileNotFoundException extends \Exception
{

    public function __construct()
    {
        parent::__construct('The file you are looking for doesnt exist', 404);
    }
}
