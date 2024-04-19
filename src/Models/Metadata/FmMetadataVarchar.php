<?php

namespace MCris112\FileSystemManager\Models\Metadata;

use Illuminate\Database\Eloquent\Model;

/**
 * MCris112\FileSystemManager\Models\Metadata\FmMetadataVarchar
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataVarchar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataVarchar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataVarchar query()
 * @mixin \Eloquent
 */
class FmMetadataVarchar extends Model
{
    protected $table = "fm_metadata_varchar";

    public $timestamps = false;

    protected $fillable = [
        'fm_file_id',
        'attr',
        'value',
    ];
}
