<?php

namespace MCris112\FileSystemManager\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * MCris112\FileSystemManager\Models\FmFolder
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $disk
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \MCris112\FileSystemManager\Collections\FmFileCollection<int, \MCris112\FileSystemManager\Models\FmFile> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FmFolder> $folders
 * @property-read int|null $folders_count
 * @method static Builder|FmFolder newModelQuery()
 * @method static Builder|FmFolder newQuery()
 * @method static Builder|FmFolder query()
 * @method static Builder|FmFolder whereCreatedAt($value)
 * @method static Builder|FmFolder whereDisk($value)
 * @method static Builder|FmFolder whereId($value)
 * @method static Builder|FmFolder whereIsParent()
 * @method static Builder|FmFolder whereName($value)
 * @method static Builder|FmFolder whereParentId($value)
 * @method static Builder|FmFolder whereUpdatedAt($value)
 * @method static Builder|FmFolder withMetadata()
 * @mixin \Eloquent
 */
class FmFolder extends Model
{

    protected $fillable = [
        'parent_id',
        'disk',
        'name'
    ];

    public function folders()
    {
        return $this->hasMany(FmFolder::class, 'parent_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(FmFile::class);
    }

    public function scopeWhereIsParent($query)
    {
        return $query->where('parent_id', null);
    }

    public function scopeWithMetadata(Builder $query)
    {
        return $query->withCount('files')
            ->withSum('files', 'size');
    }
}
