<?php

namespace MCris112\FileSystemManager;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use MCris112\FileSystemManager\Models\FmFile;

class FileSystemManagerService
{

    protected FilesystemAdapter $adapter;

    public function __construct(private ?string $disk = null)
    {
        $this->adapter = Storage::disk($this->disk);
    }

    public function list(array $search = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = FmFile::whereIsParent()->whereDoesntHave('directory');

        if($this->disk)
            $query->where('disk', $this->disk);

        if (count($search) > 0)
            $query->where($search);

        return $query->paginate();
    }

    /**
     * Get the current used size
     * @return int
     */
    public static function size(): int
    {
        return FmFile::sum('size');
    }

    public function get($id)
    {
        return FmFile::with([
            'metadataInt', 'metadataVarchar', 'metadataDateTime', 'metadataDecimal'
        ])->whereId($id)->first();
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
     * @param UploadedFile $file
     * @param bool $isPublic
     * @param int $createdBy
     * @param string $folder
     * @param ?string $name If name is not set, It will be the filename
     * @param \Closure|null $doAfterSaveFile Do something after save function( FmFileContent $fileContent, FmFile $model)
     * @return FmFile
     * @throws \Throwable
     */
    public function save(UploadedFile $file, bool $isPublic, int $createdBy, string $folder, ?string $name, ?\Closure $doAfterSaveFile = null,): FmFile
    {
        $fileContent = new FmFileContent($file, $folder, $name, $this->disk);
         // Save the file and get the model
        $model = Db::transaction( function () use ($isPublic, $fileContent, $file, $createdBy) {
            $model = FmFile::saveAsModel(
                $fileContent->getName(),
                $fileContent->getDisk(),
                $fileContent->getFolder(),
                $fileContent->getFilename(),
                $fileContent->getSize(),
                $fileContent->getFileType(),
                $fileContent->getMimeType(),
                $fileContent->getExtension(),
                $isPublic,
                $createdBy,
                $fileContent->getMetadata()
            );

            $file->storeAs($fileContent->getFullPath(), [
                'disk' => $this->disk,
            ]);

            return $model;
        });

        //If function exists, do something with the data given
        if($doAfterSaveFile)
        call_user_func($doAfterSaveFile, $fileContent, $model );

        return $model;
    }
}
