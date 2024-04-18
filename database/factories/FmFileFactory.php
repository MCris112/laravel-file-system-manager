<?php

namespace MCris112\FileSystemManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MCris112\FileSystemManager\Enums\FmFileType;
use MCris112\FileSystemManager\Models\FmFile;

class FmFileFactory extends Factory
{

    protected $model = FmFile::class;
    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        $name = fake()->text(30);
        return [
            'name' => $name,
            'path_filename' => str($name)->slug(),
            'path_folder' => 'test/',
            'disk' => fake()->randomElement( config('filesystems.disks',) )['driver'],
            'size' => fake()->numberBetween(9000000, 1000000),
            'type' => fake()->randomElement(FmFileType::cases())->value,
            'mimetype' => 'image/jpeg',
            'extension' => 'jpg',

            'is_public' => fake()->boolean(),
            'created_by' => 1
        ];
    }
}
