<?php

namespace MCris112\FileSystemManager\Models\Metadata;

use Illuminate\Database\Eloquent\Model;

/**
 * MCris112\FileSystemManager\Models\Metadata\FmMetadataInt
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataInt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataInt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataInt query()
 * @mixin \Eloquent
 */
class FmMetadataInt extends Model
{

    protected $table = "fm_metadata_int";

    public $timestamps = false;

    protected $fillable = [
        'fm_file_id',
        'attr',
        'value',
    ];
}
