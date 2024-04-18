<?php

namespace MCris112\FileSystemManager\Base;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractManager extends FileSystemManagerBase
{

//    abstract public function content(?int $perPage = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    abstract public function search(string $name, ?int $perPage = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    abstract public function find(int $id): Model;
}
