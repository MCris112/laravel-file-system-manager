<?php

namespace MCris112\FileSystemManager\Models\Metadata;

use Illuminate\Database\Eloquent\Model;

/**
 * MCris112\FileSystemManager\Models\Metadata\FmMetadataDecimal
 *
 * @property int $id
 * @property int|null $fm_file_id
 * @property string $attr
 * @property string $value
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDecimal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDecimal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDecimal query()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDecimal whereAttr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDecimal whereFmFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDecimal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDecimal whereValue($value)
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
