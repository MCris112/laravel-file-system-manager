<?php

namespace MCris112\FileSystemManager\Models\Metadata;

use Illuminate\Database\Eloquent\Model;

/**
 * MCris112\FileSystemManager\Models\Metadata\FmMetadataDatetime
 *
 * @property int $id
 * @property int|null $fm_file_id
 * @property string $attr
 * @property string $value
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDatetime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDatetime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDatetime query()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDatetime whereAttr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDatetime whereFmFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDatetime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataDatetime whereValue($value)
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
