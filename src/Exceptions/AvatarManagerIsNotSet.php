<?php

namespace MCris112\FileSystemManager\Exceptions;

class AvatarManagerIsNotSet extends \Exception
{

    public function __construct()
    {
        parent::__construct("Avatar Manager is not set", 500);
    }
}
