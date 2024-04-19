<?php

namespace MCris112\FileSystemManager;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use MCris112\FileSystemManager\Base\FileSystemManagerBase;
use MCris112\FileSystemManager\Enums\FmFileSize;
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

        return  config('app.url').'/'.'storage/'.$this->disk.'/'.$path;
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
     * @return int|string
     */
    public function size()
    {
        /** @var string|int $value */
        $value = config('filesystemmanager.storage.size.'.$this->disk);

        dump($this->_convertToBytes($value));
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


    /**
     * Save the file information
     * @param UploadedFile|string $file
     * @param FmFileSize $size
     * @param bool $isPublic
     * @param int $createdBy
     * @param string $folder
     * @param ?string $name If name is not set, It will be the filename
     * @param \Closure|null $doAfterSaveFile Do something after save function( FmFileContent $fileContent, FmFile $model)
     * @return FmFile
     * @throws \Throwable
     */
    public function save(UploadedFile|string $file, FmFileSize|string $size, bool $isPublic, int $createdBy, string $folder, ?string $name, ?int $parentId = null, ?\Closure $doAfterSaveFile = null): FmFile
    {
        $fileContent = new FmFileContent($file, $folder, $name, $this->disk);
         // Save the file and get the model
        $model = Db::transaction( function () use ($isPublic, $fileContent, $file, $createdBy, $size, $parentId) {
            $model = FmFile::saveAsModel(
                $fileContent->getName(),
                $fileContent->getDisk(),
                $fileContent->getFolder(),
                $fileContent->getFilename(),
                $fileContent->getSize(),
                $size,
                $fileContent->getFileType(),
                $fileContent->getMimeType(),
                $fileContent->getExtension(),
                $isPublic,
                $createdBy,
                $fileContent->getMetadata(),
                $parentId
            );

            $fileContent->getFile()->storeAs($fileContent->getFullPath(), [
                'disk' => $this->disk,
            ]);

            return $model;
        });

        //If function exists, do something with the data given
        if($doAfterSaveFile)
        call_user_func($doAfterSaveFile, $fileContent, $model );

        return $model;
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
     * @return FolderManager
     */
    public function folder(): FolderManager
    {
        return new FolderManager($this->disk);
    }

    /**
     * Access to File Manager
     * @return FileManager
     */
    public function file(): FileManager
    {
        return new FileManager($this->disk);
    }

    /**
     * Access to Avatar Manager
     * @return AvatarFileSystemManager
     * @throws AvatarManagerIsNotSet
     */
    public function avatar(): AvatarFileSystemManager
    {
        return new AvatarFileSystemManager($this->disk);
    }

    private function _convertToBytes(string $from): ?int {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $number = substr($from, 0, -2);
        $suffix = strtoupper(substr($from,-2));

        //B or no suffix
        if(is_numeric(substr($suffix, 0, 1))) {
            return preg_replace('/[^\d]/', '', $from);
        }

        $exponent = array_flip($units)[$suffix] ?? null;
        if($exponent === null) {
            return null;
        }

        return $number * (1024 ** $exponent);
    }

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
