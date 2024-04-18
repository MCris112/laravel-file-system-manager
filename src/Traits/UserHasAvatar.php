<?php

namespace MCris112\FileSystemManager\Traits;

use MCris112\FileSystemManager\Exceptions\AvatarManagerIsNotSet;
use MCris112\FileSystemManager\Manager\AvatarFileSystemManager;

trait UserHasAvatar
{

    public function getName()
    {
        return $this->name;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @throws AvatarManagerIsNotSet
     */
    public function avatar()
    {
        return new AvatarFileSystemManager($this->avatar_disk, $this);
    }
}
