<?php

namespace MCris112\FileSystemManager\Models\Metadata;

use Illuminate\Database\Eloquent\Model;

/**
 * MCris112\FileSystemManager\Models\Metadata\FmMetadataInt
 *
 * @property int $id
 * @property int|null $fm_file_id
 * @property string $attr
 * @property int $value
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataInt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataInt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataInt query()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataInt whereAttr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataInt whereFmFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataInt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataInt whereValue($value)
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
