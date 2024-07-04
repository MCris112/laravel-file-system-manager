<?php

namespace MCris112\FileSystemManager;

use Illuminate\Database\Eloquent\Model;
use MCris112\FileSystemManager\Models\FmFile;
use MCris112\FileSystemManager\Models\FmMetadata;
use MCris112\FileSystemManager\Models\Metadata\FmMetadataInt;
use MCris112\FileSystemManager\Models\Metadata\FmMetadataVarchar;

class FmFileContentMetadata
{

    public function __construct(
        private int $width,
        private int $height
    )
    {
    }

    /**
     * Save all metadata in this class
     * @param FmFile $model
     * @return void
     */
    public function save(FmFile $model): void
    {

        if( $this->width == 0 || $this->height == 0 ) return;

        $data = [
            'width' => $this->width,
            'height' => $this->height
        ];

        $metadata = [];

        foreach ($data as $key => $value)
        {
            $metadata[] = new FmMetadata([
                'attr' => $key,
                'value' => $value
            ]);
        }

        $model->metadata()->saveMany($metadata);
    }
}
