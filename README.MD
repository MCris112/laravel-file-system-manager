# LARAVEL FILE SYSTEM MANAGER

## How to use

### Save a file

```php
use MCris112\FileSystemManager\Facades\FileSystemManager;

FileSystemManager::save($request->file('content'),
    true, //is Public?
    1,¨// created by id (Comes from user id)
    'test/', // folder where it will be saved
    null, // name - If name is not set, It will take filename of file
    function (FmFileContent $data, FmFile $model){ // Do something after save, this can be null
        // Do something
    }
);

// This return a FmFile model
$model = FileSystemManager::save(...);
```
### Use a different disk manager
Just use the static function disk to change
```php
$model = FileSystemManager::disk('s3')->save(...);
```

# Functions
## Size used
```php
// Retrieve the size used in kilobytes
$size = FileSystemManager::size();
```

## List
Get a paginated list of files models that doesn't have a directory as parent
```php
$list = FileSystemManager::list();

// Can search by specific records - Returns CollectionsFmFileCollection
$list = FileSystemManager::list([
    ['disk', 's3'],
    ['name', 'LIKE', '%Avocado%']
]);
```

## Collection
When you get a collection of files models, It has a function to use
```php

/** @var \MCris112\FileSystemManager\CollectionsFmFileCollection $models */
$models = \MCris112\FileSystemManager\Models\FmFile::get();

// You can use
$models->toResource(); // This will return a FmFileListResource::collection()
```

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

### Customize resource transformation

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
