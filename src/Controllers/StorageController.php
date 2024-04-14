<?php

namespace MCris112\FileSystemManager\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use MCris112\FileSystemManager\Exceptions\FmFileNotFoundException;
use MCris112\FileSystemManager\Facades\FileSystemManager;
use MCris112\FileSystemManager\Models\FmFile;

class StorageController extends Controller
{

    /**
     * @throws FmFileNotFoundException
     */
    public function index(string $disk, string $path)
    {
        $model = FileSystemManager::disk($disk)->find($path);

        /** @var User|null $authUser */
        $authUser = config('filesystemmanager.auth.user')() ?? null;

        if(!$model->is_public && !$authUser?->canViewFmFiePrivate() ) throw new FmFileNotFoundException;

        return \Storage::disk($disk)->response($path);
    }
}
