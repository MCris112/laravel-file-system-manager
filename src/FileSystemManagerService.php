<?php

namespace MCris112\FileSystemManager;

use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use MCris112\FileSystemManager\Base\FileSystemManagerBase;
use MCris112\FileSystemManager\Exceptions\AvatarManagerIsNotSet;
use MCris112\FileSystemManager\Manager\AvatarFileSystemManager;
use MCris112\FileSystemManager\Manager\FileManager;
use MCris112\FileSystemManager\Manager\FolderManager;
use MCris112\FileSystemManager\Models\FmFile;
use MCris112\FileSystemManager\Models\FmFolder;

class FileSystemManagerService extends FileSystemManagerBase
{

    public function url(FmFile|string $modelOrPath): string
    {
        $path = $modelOrPath;

        if($modelOrPath instanceof FmFile) $path = $modelOrPath->path_folder.$modelOrPath->path_filename;

        return url('storage/'.$this->disk.'/'.$path);
    }

    /**
     * Get all used size based on disk with Files and Avatars
     * @return int
     */
    public function used(): int
    {
        $avatar = 0;

        try {
            $avatar = $this->avatar()->used();
        }catch (\Exception $e) {}

        return $this->file()->used() + $avatar;
    }

    /**
     * Get the storage size
     * @return int
     */
    public function size(): int
    {
        /** @var string|int $value */
        $value = config('filesystemmanager.storage.size.'.$this->disk);

        if(is_string($value))
        return $this->_convertToBytes($value);

        return $value ?? 0;
    }

    /**
     * How much storage left
     * @return int
     */
    public function left(): int
    {
        return $this->size() - $this->used();
    }

    /**
     * Set the disk to use
     * @param string $name
     * @return self
     */
    public function disk(string $name): self
    {
        return new self($name);
    }

    /***************************************************
     *
     *  CONTENT FUNCTIONS
     *
     */

    /**
     * Get this to show it into your file manager interface
     * @param int|null $load Number of how many files will be loaded
     * @return FileManagerContent
     */
    public function content(?int $load = null): FileManagerContent
    {
        return new FileManagerContent(
            FmFolder::whereDisk($this->disk)->whereIsParent()->withMetadata()->get(),
            FmFile::whereDisk($this->disk)->whereIsParent()->whereDoesntHave('folder')->paginate($load)
        );
    }

    /**
     * Access to Folder Manager
     * @param FmFolder|int|null $parent
     * @return FolderManager
     */
    public function folder(FmFolder|int|null $parent = null): FolderManager
    {
        return new FolderManager($this->disk, $parent);
    }

    /**
     * Access to File Manager
     * @param FmFile|int|null $parent
     * @return FileManager
     */
    public function file(FmFile|int|null $parent = null): FileManager
    {
        return new FileManager($this->disk, $parent);
    }

    /**
     * Access to Avatar Manager
     * @return AvatarFileSystemManager
     * @throws AvatarManagerIsNotSet
     */
    public function avatar(?User $user = null): AvatarFileSystemManager
    {
        return new AvatarFileSystemManager($this->disk, $user);
    }

    private function _convertToBytes(string $from): int {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $number = substr($from, 0, -2);
        $suffix = strtoupper(substr($from,-2));

        //B or no suffix
        if(is_numeric(substr($suffix, 0, 1))) {
            return preg_replace('/[^\d]/', '', $from);
        }

        $exponent = array_flip($units)[$suffix] ?? null;
        if($exponent === null) {
            return 0;
        }

        return $number * (1024 ** $exponent);
    }

    /**
     * Convert a base64 encoded into a UploadedFile
     * @param string $base64File
     * @return UploadedFile
     */
    public function fromBase64(string $base64File): UploadedFile
    {

        // Get file data base64 string
        $fileData = base64_decode(Arr::last(explode(',', $base64File)));

        // Create temp file and get its absolute path
        $tempFile = tmpfile();
        $tempFilePath = stream_get_meta_data($tempFile)['uri'];

        // Save file data in file
        file_put_contents($tempFilePath, $fileData);

        $tempFileObject = new File($tempFilePath);

        $file = new UploadedFile(
            $tempFileObject->getPathname(),
            $tempFileObject->getFilename(),
            $tempFileObject->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );

        // Close this file after response is sent.
        // Closing the file will cause to remove it from temp director!
        app()->terminating(function () use ($tempFile) {
            fclose($tempFile);
        });

        // return UploadedFile object
        return $file;
    }
}
