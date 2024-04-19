<?php

namespace MCris112\FileSystemManager\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MCris112\FileSystemManager\Models\FmMetadata
 *
 * @property int $id
 * @property int|null $fm_file_id
 * @property string $attr
 * @property string $value
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadata newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadata newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadata query()
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadata whereAttr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadata whereFmFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadata whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmMetadata whereValue($value)
 * @mixin \Eloquent
 */
class FmMetadata extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'fm_file_id',
        'attr',
        'value',
    ];
}
