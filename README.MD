# LARAVEL FILE SYSTEM MANAGER
---

**Laravel File System Manager** is a project to upload/view files and retrieve as resource to show it into your frontend file manager interface
``If u have some recommendations, please do.``

* **Author**: MCris112
* **Vendor**: mcris112
* **Package**: laravel-file-system-manager
* **Version**: `1.x`
* **PHP Version**: 8.1+
* **Laravel Version**: `10.x`

# Table of Contents
---
1. [Installation](#installation)
   1. [Configuration](#configuration)
2. [Getting Started](#getting-started)
   1. [Save a file](#save-a-file)
   2. [Use a different disk manager](#use-a-different-disk-manager)
3. [Functions](#functions)
   1. [Url](#url)
   2. [Manage Size](#manage-size)
   3. [Content](#content)
   4. [From Base 64](#from-base-64)
   5. [Collection](#collection)
4. [Traits](#traits)
   1. [Resource](#resource)
      1. [Customize FmFile resource transformation](#customize-resource-transformation)
5. [Category](#category)
   1. [Folder](#folder)
      * [Create Folder](#create-folder)
      * [Content](#folder-content)
      * [Search Folders](#search-folders)
      * [Find a Folder](#find-a-folder)
      * [Folder Parent](#folder-parent)
      * [Put into Folder](#put-into-folder)
      * [Delete folder or content](#delete-folder-or-content)
   2. [File](#file)
      1. [Search files](#search-files)
      2. [Find a file](#find-a-file)
   3. [Avatar](#avatar)
      1. [Before use](#before-use)
      2. [Used](#used)
      3. [Url](#avatar-url)
      4. [Create Avatar](#create-avatar)
# Installation
Install the package with the command
**[Composer](https://getcomposer.org/):**
```php
composer require mcris112/laravel-file-system-manager
```

## Configuration
Publish the config file

```shell
php artisan vendor:publish --tag="filesystemmanager.config"
```

Add/delete the disk driver size that you will use
```php
return [
    "storage" => [
        /********************************
         *
         * Define here what storage disk drivers should be supported
         * and the storage size has each driver
         *
         */
        'size' => [
            "local" => env('FM_STORAGE_LOCAL_SIZE', "1GB"),
            "public" => env('FM_STORAGE_PUBLIC_SIZE', "1GB"),
            //"s3" => env('FM_STORAGE_S3_SIZE', 0),
        ],
    ],
```


# Getting Started
## Save a file
```php
use MCris112\FileSystemManager\Facades\FileSystemManager;

FileSystemManager::file()->save(
    $request->file('content'),
    true, //is Public?
    1,¨// created by id (Comes from user id)
    'test/', // folder where it will be saved
    null, // name - If name is not set, It will take filename of file
    function (FmFileContent $data, FmFile $model){ // Do something after save, this can be null
        // Do something
    }
);

// This return a FmFile model
$model = FileSystemManager::file()->save(...);
```
## Use a different disk manager
Just use the static function disk to change
```php
$model = FileSystemManager::disk('s3')->file()->save(...);
```

# Functions

## Url
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;
use \MCris112\FileSystemManager\Models\FmFile;

// Get URL by model
$url = FileSystemManager::url( FmFile::first() );

// Get by path
$url = FileSystemManager::url( "documents/your-filename.jpg" );
```
## Manage Size
Get global information that is set into config and content saved
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;

// Retrieve the size used in kilobytes
$size = FileSystemManager::used();

// All size allowed
$size = FileSystemManager::size();

// How much space is left
$size = FileSystemManager::left();
```
Get information by specific type of content
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;

// Get how much storage file is using
$size = FileSystemManager::file()->used();

// Get how much storage Avatar is using
$size = FileSystemManager::avatar()->used();
```
## Content
Get this to show it into your file manager interface.
Get a paginated list of files models that doesn't have a directory as parent
```php
$list = FileSystemManager::content();

$load = 15;
// Can search by specific records - Returns CollectionsFmFileCollection
$list = FileSystemManager::list(
    $load // -> How many files will get in paginated list
);
```

## From Base 64
Convert a base64 encoded into a UploadedFile
```php
/** @var Illuminate\Http\UploadedFile $list */
$list = FileSystemManager::fromBase64("base64...");
```

## Collection
When you get a collection of files models, It has a function to use
```php
use \MCris112\FileSystemManager\Models\FmFile;
use \MCris112\FileSystemManager\Collections\FmFileCollection;


/** @var FmFileCollection $models */
$models = FmFile::get();

// You can use
$models->toResource(); // This will return a FmFileListResource::collection()
```

# Traits
## Resource
Convert relation at resource to frontend response. Use the trait `ResourceFmImageVariationTransformation` to use the function
`$this->fmImageVariation()`

```php
use MCris112\FileSystemManager\Traits\Resource\ResourceFmImageVariationTransformation;

class ProductResource extends JsonResource
{
    use ResourceFmImageVariationTransformation;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            "name" => $this->name,

            // This function converts the normal relation into a complex array
            'image' => $this->fmImageVariation(),

            "timestamps" => [
                "createdAt" => $this->created_at,
                "updatedAt" => $this->updated_at,
            ]
        ];
    }

}
```

#### Before
```php
{
    "id": 1,
    "name": "Aspernatur voluptas non illum. Ut sit suscipit explicabo.",
    "image": [
        {
            "id": 32,
            "filename": "..."
            //....
            "pivot": {
                //...
                "field": "full"
            }
        },
        {
            "id": 33,
            "filename": "..."
            //....
            "pivot": {
                //...
                "field": "thumbnail"
            }
        }
    ],
    "timestamps": {
        "createdAt": "2024-04-13T17:33:41.000000Z",
        "updatedAt": "2024-04-13T17:33:41.000000Z"
    }
}

```

#### After

```php
{
    "id": 1,
    "name": "Aspernatur voluptas non illum. Ut sit suscipit explicabo.",
    "image": {
        "full": {
            //.... FmFileListResource
        },
        "thumbnail": {
            //.... FmFileListResource
        }
    },
    "timestamps": {
        "createdAt": "2024-04-13T17:33:41.000000Z",
        "updatedAt": "2024-04-13T17:33:41.000000Z"
    }
}

```

## Customize resource transformation

if you want to customize the resource to use in function `$this->fmImageVariation()`, just need to change the class in
```php
use MCris112\FileSystemManager\Traits\Resource\ResourceFmImageVariationTransformation;

class ProductResource extends JsonResource
{
    use ResourceFmImageVariationTransformation;

    //Replace with your custom resource
    protected string $fmImageResource = FmFileListResource::class;
}
```

# Category

## Folder

### Create Folder
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;


$folderManager = FileSystemManager::folder()->create("Name of folder");
```

### Folder Content
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;

/**
 * Get the parents of files and folder to show it into the file manager interface
 * @param int $id
 * @param int|null $perPage
 * @return \MCris112\FileSystemManager\FileManagerContent
 */
$content = FileSystemManager::folder()->content( $folderId )

```
### Search Folders
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;

/**
* @param string $name
* @param int|null $perPage
* @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
*/
$content = FileSystemManager::folder()->search( "content" )

```
### Find a folder
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;

/**
 * @param int $id
 * @return \MCris112\FileSystemManager\Models\FmFolder
 */
$content = FileSystemManager::folder()->find( $id )
```

### Folder Parent
If you want to create a sub folder, you can define the parent
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;


$folderManager = FileSystemManager::folder( $FmFodlerOrIntOrNullableValue );

//Now will create a sub folder from parent folder
$folderManager->create("Sub Folder Name");

//For any case you couldnt set a parent folder, you can set it
$folderManager->parent($FmFodlerOrIntOrNullableValue)->create();
```

### Put into folder
To save some content inside of folder, just do
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;


$folderManager = FileSystemManager::folder( $FmFodlerOrIntOrNullableValue );

//Now will create a sub folder from parent folder
$folderManager->create("Sub Folder Name");

//For any case you couldnt set a parent folder, you can set it
$folderManager->parent($FmFodlerOrIntOrNullableValue)->create();
```

### Delete folder or content
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;

// For delete an specific file or content
$folderManager = FileSystemManager::folder($folder)->delete($fmFileOrFmFolderOrArray);

// For delete the folder
$folderManager = FileSystemManager::folder($folder)->delete();

// For delete the folder and the content inside
$folderManager = FileSystemManager::folder($folder)->delete(null , true);
```

## File
### Search Files
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;

/**
* @param string $name
* @param int|null $perPage
* @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
*/
$content = FileSystemManager::folder()->search( "content" )
```
### Find a File
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;

/**
 * Find a file by id or full path (folder + filename)
 * @param int|string $idOrPath
 * @return FmFile
 * @throws FmFileNotFoundException
 */
$content = FileSystemManager::folder()->find( $id )
```
Why `FileSystemManager::folder()->find( $id )` instead of `FmFile::find($id)` or `FmFile::whereId($id)->get()`, you can use it but in this case will return a full cached model.

### Delete file
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;

$folderManager = FileSystemManager::file($file)->delete();

//or

/** @var \MCris112\FileSystemManager\Models\FmFile $file */
$file->delete(); // Automatically It will delete the file in your disk
```
Why use `FileSystemManager::file($file)->delete()` instead of `$file->delete()`, in this case, if something happens when It's destroying your file, the record on your db won't be rolled back and It will generate an incorrect sum of storage size

## Avatar

### Before Use

#### Config
Dont forget to configure `filesystemmanager.php` in case you want to use avatars
```php
return [
    //...
    "user" => [
        "avatar" => true, // Set this true to start using avatars
        "width" => 512, // Avatar width
        "height" => 512, // Avatar height
        "quality" => 80, // file quality of JPG
        "generatorUrl" => "https://ui-avatars.com/api/?size=512&name="
    ]
]
```
Add `UserHasAvatar` trait to avoid errors when is null to generate a based name avatar, but if you always will set a file, you can avoid this step
```php
use MCris112\FileSystemManager\Traits\UserHasAvatar;

class User extends Authenticatable
{
    use UserHasAvatar;

    //...
}
```

### Used
How much storage is the avatar using
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;

$avatarManager = FileSystemManager::avatar()->used()
```

### Avatar Url
Get the Avatar url from User
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;

$user = \App\Models\User::first();

$avatarManager = FileSystemManager::avatar()->user($user)->url();

//or
$avatarManager = FileSystemManager::avatar($user)->url();

//or
$user->avatar()->url();
```
### Create Avatar
You can define the file, but as same time you can let it as null and It will create an avatar based on name
```php
use \MCris112\FileSystemManager\Facades\FileSystemManager;


/**
 * @param UploadedFile|null $file
 * @return User
 */
public function create(?UploadedFile $file = null): User
{
    //...
}

$user = \App\Models\User::first();

FileSystemManager::avatar()->user($user)->create($file);

//or
FileSystemManager::avatar($user)->create($file);

//or
$user->avatar()->create($file);
```
