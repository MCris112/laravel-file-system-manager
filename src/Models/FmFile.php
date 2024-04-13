<?php

namespace MCris112\FileSystemManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use MCris112\FileSystemManager\Collections\FmFileCollection;
use MCris112\FileSystemManager\Database\Factories\FmFileFactory;
use MCris112\FileSystemManager\Enums\FmFileType;
use MCris112\FileSystemManager\FmFileContentMetadata;
use MCris112\FileSystemManager\Models\Metadata\FmMetadataDatetime;
use MCris112\FileSystemManager\Models\Metadata\FmMetadataDecimal;
use MCris112\FileSystemManager\Models\Metadata\FmMetadataInt;
use MCris112\FileSystemManager\Models\Metadata\FmMetadataVarchar;

/**
 * MCris112\FileSystemManager\Models\FmFile
 *
 * @property int $id
 * @property int|null $fm_directory_id
 * @property string $name
 * @property string $path_filename
 * @property string $path_folder
 * @property string $disk
 * @property int $size
 * @property string $type
 * @property string $mimetype
 * @property string $extension
 * @property int $is_public
 * @property int|null $parent_id
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \MCris112\FileSystemManager\Models\FmDirectory|null $directory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FmMetadataDatetime> $metadataDatetime
 * @property-read int|null $metadata_datetime_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FmMetadataDecimal> $metadataDecimal
 * @property-read int|null $metadata_decimal_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FmMetadataInt> $metadataInt
 * @property-read int|null $metadata_int_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FmMetadataVarchar> $metadataVarchar
 * @property-read int|null $metadata_varchar_count
 * @method static FmFileCollection<int, static> all($columns = ['*'])
 * @method static \MCris112\FileSystemManager\Database\Factories\FmFileFactory factory($count = null, $state = [])
 * @method static FmFileCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereFmDirectoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereIsParent()
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereMimetype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile wherePathFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile wherePathFolder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FmFile withMetadata()
 * @mixin \Eloquent
 */
class FmFile extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'path_filename',
        'path_folder',
        'disk',
        'size',
        'type',
        'mimetype',
        'extension',
        'is_public',
        'created_by',
        'parent_id'
    ];

    /**
     * Use args provided to create a new model with metadata information
     * @param string $name
     * @param string $disk
     * @param string $folder
     * @param string $filename
     * @param int $size
     * @param FmFileType $type
     * @param string $mimetype
     * @param string $extension
     * @param bool $isPublic
     * @param int $createdBy
     * @param FmFileContentMetadata $metadata
     * @return self
     */
    public static function saveAsModel(
        string $name,
        string $disk,
        string $folder,
        string $filename,
        int $size,
        FmFileType $type,
        string $mimetype,
        string $extension,
        bool $isPublic,
        int $createdBy,
        FmFileContentMetadata $metadata): self
    {
        $model = FmFile::create([
            'name' => $name,
            'path_filename' => $filename,
            'path_folder' => $folder,
            'disk' => $disk,
            'size' => $size,
            'type' => $type->value,
            'mimetype' => $mimetype,
            'extension' => $extension,
            'is_public' => $isPublic,
            'created_by' => $createdBy,
            'parent_id' => null // TODO when model is a variation
        ]);

        //Saving each type of metadata into the model
        $metadata->save($model);

        return $model;
    }

    protected static function newFactory(): FmFileFactory
    {
        return FmFileFactory::new();
    }

    public function newCollection(array $models = []): FmFileCollection
    {
        return new FmFileCollection($models);
    }

    public function directory()
    {
        return $this->belongsTo(FmDirectory::class, 'fm_directory_id', 'id');
    }

    public function scopeWithMetadata($query)
    {
        return $query->with(['metadataInt', 'metadataVarchar', 'metadataDatetime', 'metadataDecimal']);
    }

    public function metadataInt(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FmMetadataInt::class);
    }

    public function metadataVarchar()
    {
        return $this->hasMany(FmMetadataVarchar::class);
    }

    public function metadataDatetime()
    {
        return $this->hasMany(FmMetadataDatetime::class);
    }

    public function metadataDecimal()
    {
        return $this->hasMany(FmMetadataDecimal::class);
    }

    public function metadata()
    {
        $metadata = new Collection;

        $metadata = $metadata->concat($this->metadataInt)
            ->concat($this->metadataVarchar)
            ->concat($this->metadataDatetime)
            ->concat($this->metadataDecimal);

        return $metadata;
    }

    public function scopeWhereIsParent($query)
    {
        return $query->where('is_public', 1);
    }
}
