<?php

namespace MCris112\FileSystemManager\Manager;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use MCris112\FileSystemManager\Base\FileSystemManagerBase;
use MCris112\FileSystemManager\Exceptions\AvatarManagerIsNotSet;
use Symfony\Component\HttpKernel\Attribute\Cache;

class AvatarFileSystemManager extends FileSystemManagerBase
{
    public function __construct(string $disk, protected  ?User $user = null)
    {
        if(!config('filesystemmanager.user.avatar')) throw new AvatarManagerIsNotSet;
        parent::__construct($disk);
    }

    public function user(User $user)
    {
        return new self($this->disk, $user);
    }

    public function url()
    {
        return config('app.url') . '/' .'storage/'.$this->disk.'/'.$this->getPath();
    }

    /**
     * @param UploadedFile|null $file
     * @return User
     */
    public function create(?UploadedFile $file = null): User
    {
        $storage = Storage::disk($this->disk);
        $manager = ImageManager::gd();
        $quality = config('filesystemmanager.user.quality');
        $fileCreated = false;

        if($file)
        {
            $image = $manager->read($file);

            $image = $image->cover(config('filesystemmanager.user.width'), config('filesystemmanager.user.height'));

            $storage->put($this->getPath(), $image->toJpeg($quality)->toFilePointer());
            $fileCreated = true;
        }else{
            $image = $manager->read(
                file_get_contents(config('filesystemmanager.user.generatorUrl').urlencode($this->user->getName()).'+'.urlencode($this->user->getLastname()))
            );

            $storage->put($this->getPath(), $image->toJpeg($quality)->toFilePointer());
            $fileCreated = true;
        }


        $this->user->avatar_disk = $this->disk;
        $this->user->avatar_size = $storage->size($this->getPath());

        try {
            $this->user->save();
            \Cache::forget('fm_storage_avatars_used');
        }catch(\Exception $e)
        {
            if($fileCreated) $storage->delete($this->getPath());
        }

        return $this->user;
    }

    public function getPath(): string
    {
        return "users/user-".$this->user->getKey().'.jpg';
    }

    /**
     * Get used size of all avatars based on disk
     * @return int
     */
    public function used(): int
    {
        return \Cache::rememberForever('fm_storage_avatars_used',
            fn() => User::whereAvatarDisk($this->disk)->sum('avatar_size')
        );
    }
}
