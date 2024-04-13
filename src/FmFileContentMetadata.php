<?php

namespace MCris112\FileSystemManager;

use Illuminate\Database\Eloquent\Model;
use MCris112\FileSystemManager\Models\FmFile;
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
        $this->saveInt($model);
        $this->saveVarchar($model);
    }

    private function saveInt(FmFile $model): void
    {
        $meta = [
            'width' => $this->width,
            'height' => $this->height
        ];
        $data = $this->newMetadataModels($meta, FmMetadataInt::class);

        if($data) $model->metadataInt()->saveMany($data);
    }
    private function saveVarchar(FmFile $model): void
    {
        $meta = [];
        $data = $this->newMetadataModels($meta, FmMetadataVarchar::class);

        if($data) $model->metadataVarchar()->saveMany($data);
    }

    private function newMetadataModels(array $data, string $model): array
    {
        $metas = [];

        foreach ($data as $key => $value)
        {
            $metas[] = new $model([
                'attr' => $key,
                'value' => $value
            ]);
        }

        return $metas;
    }
}
