<?php

namespace MCris112\FileSystemManager\Observers;

use Illuminate\Support\Facades\Cache;
use MCris112\FileSystemManager\Models\FmFile;

class FmFileObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(FmFile $model): void
    {
        $this->updateCached($model);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(FmFile $model): void
    {
        $this->updateCached($model);
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(FmFile $model): void
    {
        $this->updateCached($model);

        \Storage::disk($model->disk)->delete($model->getPath());
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(FmFile $model): void
    {
        $this->updateCached($model);
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(FmFile $model): void
    {
        $this->updateCached($model);
        \Storage::disk($model->disk)->delete($model->getPath());
    }

    private function updateCached(FmFile $model)
    {
        Cache::forget('fm_storage_files_used');
        Cache::forget('fm_file_find_'.str($model->id)->slug());
        Cache::forget('fm_file_find_'.str($model->path_folder.$model->path_filename)->slug());
    }
}
