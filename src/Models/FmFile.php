<?php

namespace MCris112\FileSystemManager\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\ImageManager;
use MCris112\FileSystemManager\Collections\FmFileCollection;
use MCris112\FileSystemManager\Database\Factories\FmFileFactory;
use MCris112\FileSystemManager\Enums\FmFileSize;
use MCris112\FileSystemManager\Enums\FmFileType;
use MCris112\FileSystemManager\Facades\FileSystemManager;
use MCris112\FileSystemManager\FmFileContentMetadata;
use MCris112\FileSystemManager\Models\Metadata\FmMetadataDatetime;
use MCris112\FileSystemManager\Models\Metadata\FmMetadataDecimal;
use MCris112\FileSystemManager\Models\Metadata\FmMetadataInt;
use MCris112\FileSystemManager\Models\Metadata\FmMetadataVarchar;
use MCris112\FileSystemManager\Observers\FmFileObserver;

/**
 * MCris112\FileSystemManager\Models\FmFile
 *
 * @property int $id
 * @property int|null $fm_folder_id
 * @property string $name
 * @property string $path_filename
 * @property string $path_folder
 * @property string $disk
 * @property int $size
 * @property string $size_type
 * @property string $type
 * @property string $mimetype
 * @property string $extension
 * @property int $is_public
 * @property int|null $parent_id
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \MCris112\FileSystemManager\Models\FmFolder|null $folder
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \MCris112\FileSystemManager\Models\FmMetadata> $metadata
 * @property-read int|null $metadata_count
 * @property-read FmFileCollection<int, FmFile> $variations
 * @property-read int|null $variations_count
 * @method static FmFileCollection<int, static> all($columns = ['*'])
 * @method static \MCris112\FileSystemManager\Database\Factories\FmFileFactory factory($count = null, $state = [])
 * @method static FmFileCollection<int, static> get($columns = ['*'])
 * @method static Builder|FmFile newModelQuery()
 * @method static Builder|FmFile newQuery()
 * @method static Builder|FmFile query()
 * @method static Builder|FmFile whereCreatedAt($value)
 * @method static Builder|FmFile whereCreatedBy($value)
 * @method static Builder|FmFile whereDisk($value)
 * @method static Builder|FmFile whereExtension($value)
 * @method static Builder|FmFile whereFmFolderId($value)
 * @method static Builder|FmFile whereId($value)
 * @method static Builder|FmFile whereIsParent()
 * @method static Builder|FmFile whereIsPublic($value)
 * @method static Builder|FmFile whereMimetype($value)
 * @method static Builder|FmFile whereName($value)
 * @method static Builder|FmFile whereParentId($value)
 * @method static Builder|FmFile wherePathFilename($value)
 * @method static Builder|FmFile wherePathFolder($value)
 * @method static Builder|FmFile whereSize($value)
 * @method static Builder|FmFile whereSizeType($value)
 * @method static Builder|FmFile whereType($value)
 * @method static Builder|FmFile whereUpdatedAt($value)
 * @method static Builder|FmFile withVariation(\MCris112\FileSystemManager\Enums\FmFileSize $size)
 * @mixin \Eloquent
 */
#[ObservedBy([FmFileObserver::class])]
class FmFile extends Model
{

    use HasFactory;

    protected $fillable = [
        'fm_folder_id',
        'name',
        'path_filename',
        'path_folder',
        'disk',
        'size',
        'size_type',
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
        FmFileSize|string $sizeType,
        FmFileType $type,
        string $mimetype,
        string $extension,
        bool $isPublic,
        int $createdBy,
        FmFileContentMetadata $metadata,
        ?int $parentId = null): self
    {
        $model = FmFile::create([
            'name' => $name,
            'path_filename' => $filename,
            'path_folder' => $folder,
            'disk' => $disk,
            'size' => $size,
            'size_type' => $sizeType,
            'type' => $type->value,
            'mimetype' => $mimetype,
            'extension' => $extension,
            'is_public' => $isPublic,
            'created_by' => $createdBy,
            'parent_id' => $parentId
        ]);

        //Saving each type of metadata into the model
        $metadata->save($model);

        return $model;
    }

    public function getPath(): string
    {
        return $this->path_folder.$this->path_filename;
    }

    protected static function newFactory(): FmFileFactory
    {
        return FmFileFactory::new();
    }

    public function newCollection(array $models = []): FmFileCollection
    {
        return new FmFileCollection($models);
    }

    public function scopeWhereIsParent($query)
    {
        return $query->where('parent_id', null);
    }

    public function scopeWithVariation(Builder $query, FmFileSize|string|array $size): Builder
    {
        $sizeType = $size;
        if($size instanceof FmFileSize) $sizeType = [$size->value];
        if(is_string($size)) $sizeType  = [$size];

        for ($i = 0; $i < count($sizeType); $i++)
        {
            if($sizeType[$i] instanceof FmFileSize) $sizeType[$i] = $sizeType[$i]->value;
        }

        return $query->with('variations', function($q) use ($sizeType){
            return $q->whereIn('size_type', $sizeType);
        });
    }

    public function folder()
    {
        return $this->belongsTo(FmFolder::class,'fm_folder_id');
    }

    public function metadata()
    {
        return $this->hasMany(FmMetadata::class);
    }

    public function variations()
    {
        return $this->hasMany(FmFile::class, 'parent_id');
    }

    /**
     * @throws \Throwable
     */
    public function variation(FmFileSize|string $size, int $createdBy, int $width = 0, int $height = 0)
    {
        if($this->type != FmFileType::IMAGE->value) throw new \InvalidArgumentException("File is not an Image");
        if( $size instanceof FmFileSize && $size == FmFileSize::FULL ) throw new \InvalidArgumentException("This has to be a variation");
        if($this->parent_id) throw new \InvalidArgumentException("This is already a variation");

        if(!$this->relationLoaded('variations')) $this->load('variations');


        if($this->variations->filter(function (FmFile $file) use ($size) {
            return $file->size_type == ($size->value ?? $size);
        })->first()) throw new \InvalidArgumentException("Variation already exists");

        $manager = ImageManager::gd();
        $image = $manager->read(\Storage::disk($this->disk)->get($this->getPath()));

        $sizeType = $size;
        if(is_string($sizeType)) $sizeType = FmFileSize::fromConfig($size);


        $image = $image->cover(
            $width == 0 ? $sizeType->getWidth() : $width,
            $height == 0 ? $sizeType->getHeight(): $height
        );

        return FileSystemManager::file($this)->save(
            $image->encode(new AutoEncoder($this->extension))->toDataUri(),
            $size,
            $this->is_public,
            $createdBy,
            $this->path_folder,
            $this->name.' '.($size->value ?? $size)
        );

    }
}
