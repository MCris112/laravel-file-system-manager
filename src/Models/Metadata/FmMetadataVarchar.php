<?php

namespace MCris112\FileSystemManager\Models\Metadata;

use Illuminate\Database\Eloquent\Model;

/**
 * MCris112\FileSystemManager\Models\Metadata\FmMetadataVarchar
 *
 * @property int $id
 * @property int|null $fm_file_id
 * @property string $attr
 * @property string $value
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataVarchar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataVarchar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataVarchar query()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataVarchar whereAttr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataVarchar whereFmFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataVarchar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadataVarchar whereValue($value)
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
