<?php

namespace MCris112\FileSystemManager;

class FmTypeSize
{

    public function __construct(private int $width, private int $height)
    {
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }
}
