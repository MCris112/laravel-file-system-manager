<?php

namespace MCris112\FileSystemManager\Models\Metadata;

use Illuminate\Database\Eloquent\Model;

/**
 * MCris112\FileSystemManager\Models\Metadata\FmMetadataDecimal
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDecimal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDecimal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDecimal query()
 * @mixin \Eloquent
 */
class FmMetadataDecimal extends Model
{
    protected $table = "fm_metadata_decimal";

    public $timestamps = false;

    protected $fillable = [
        'fm_file_id',
        'attr',
        'value',
    ];
}
