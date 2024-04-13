<?php

namespace MCris112\FileSystemManager\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MCris112\FileSystemManager\Models\FmDirectory
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FmDirectory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmDirectory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmDirectory query()
 * @method static \Illuminate\Database\Eloquent\Builder|FmDirectory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmDirectory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmDirectory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmDirectory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmDirectory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FmDirectory extends Model
{

}
