<?php

namespace MCris112\FileSystemManager\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use MCris112\FileSystemManager\Enums\FmFileSize;
use MCris112\FileSystemManager\Models\FmFile;

trait HasFmImageVariations
{
    public function fmimage(): MorphToMany
    {
        return $this->morphToMany(FmFile::class, 'fileable', 'fm_file_morphs')->withPivot('field');
    }

    public function scopeWithThumbnail(Builder $query): Builder
    {
        return $query->with('fmimage', function( MorphToMany $q){
            return $q->wherePivot('field', FmFileSize::THUMBNAIL);
        });
    }
    public function scopeWithSquareImage(Builder $query): Builder
    {
        return $query->with('fmimage', function( MorphToMany $q){
            return $q->wherePivot('field', FmFileSize::SQUARE);
        });
    }

    /**
     * Load all or specific image size
     * @param Builder $query
     * @param array|string|null $sizes can be '*'|null for load all image sizes or an array for load specific image sizes
     * @return Builder
     */
    public function scopeWithFmImageSize(Builder $query, array|string|null $sizes = null): Builder
    {
        return $query->with('fmimage', function( MorphToMany $q) use ($sizes){

            if(is_array($sizes))
            return $q->wherePivotIn('field', $sizes);

            if($sizes == '*' || !$sizes) return $q;

            $q->wherePivot('field', $sizes);
        });
    }

}
