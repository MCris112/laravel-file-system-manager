<?php

namespace MCris112\FileSystemManager\Models\Metadata;

use Illuminate\Database\Eloquent\Model;

/**
 * MCris112\FileSystemManager\Models\Metadata\FmMetadataDatetime
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDatetime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDatetime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDatetime query()
 * @mixin \Eloquent
 */
class FmMetadataDatetime extends Model
{
    protected $table = "fm_metadata_datetime";

    public $timestamps = false;

    protected $fillable = [
        'fm_file_id',
        'attr',
        'value',
    ];
}
