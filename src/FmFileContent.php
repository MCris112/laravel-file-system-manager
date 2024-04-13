<?php

namespace MCris112\FileSystemManager;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use MCris112\FileSystemManager\Enums\FmFileType;
use MCris112\FileSystemManager\Models\FmFile;

class FmFileContent
{
    private string $mimeType;

    private string $extension;

    private int $size;

    private FmFileContentMetadata $metadata;


    public function __construct(private UploadedFile $file, private string $folder, private ?string $name = null, private readonly ?string $disk = null)
    {

        // Set the filename of the archive
        if(!$this->name)
        $this->name = $file->getClientOriginalName();

        $this->size = $file->getSize();
        $this->mimeType = $file->getClientMimeType();

        $this->extension = $file->getClientOriginalExtension();

        if ( $this->getFileType() == FmFileType::IMAGE )
        {
            $manager = ImageManager::gd()->read( $file->getContent() );

            $this->metadata = new FmFileContentMetadata(
                $manager->width(),
                $manager->height()
            );
        }
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return \Str::headline($this->name);
    }
    /**
     * @return string
     */
    public function getFilename(): string
    {
        return strtolower(\Str::slug($this->name)).'.'.$this->extension;
    }

    public function getFullPath()
    {
        return $this->folder.$this->getFilename();
    }

    public function getFileType(): FmFileType
    {
        if( str_contains($this->mimeType, 'image/') ) return FmFileType::IMAGE;

        return FmFileType::DOCUMENT;
    }

    /**
     * @return string|null
     */
    public function getDisk(): ?string
    {
        return $this->disk;
    }

    /**
     * @return string
     */
    public function getFolder(): string
    {
        return $this->folder;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return FmFileContentMetadata
     */
    public function getMetadata(): FmFileContentMetadata
    {
        return $this->metadata;
    }
}
