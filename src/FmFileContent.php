<?php

namespace MCris112\FileSystemManager;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use MCris112\FileSystemManager\Enums\FmFileType;
use MCris112\FileSystemManager\Facades\FileSystemManager;
use MCris112\FileSystemManager\Models\FmFile;

class FmFileContent
{
    private string $mimeType;

    private string $extension;

    private int $size;

    private FmFileContentMetadata $metadata;

    private UploadedFile $file;

    public function __construct(UploadedFile|string $content, private string $folder, private ?string $name = null, private readonly ?string $disk = null)
    {


        if(!$content instanceof UploadedFile)
        {

            $this->file = FileSystemManager::fromBase64($content);
        }else{
            $this->file = $content;
        }

        // Set the filename of the archive
        if(!$this->name)
        $this->name = $this->file->getClientOriginalName();

        $this->size = $this->file->getSize();
        $this->mimeType = $this->file->getClientMimeType();

        $this->extension = explode('/', $this->mimeType )[1];

        if ( $this->getFileType() == FmFileType::IMAGE )
        {
            $manager = ImageManager::gd()->read( $this->file->getContent() );

            $this->metadata = new FmFileContentMetadata(
                $manager->width(),
                $manager->height()
            );
        }
    }


    /**
     * @return UploadedFile
     */
    public function getFile(): UploadedFile
    {
        return $this->file;
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
