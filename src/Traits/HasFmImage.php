<?php

namespace MCris112\FileSystemManager\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use MCris112\FileSystemManager\Models\FmFile;

trait HasFmImage
{

    public function image(): HasOne
    {
        return $this->hasOne( FmFile::class, 'id', 'fm_file_id');
    }
}
