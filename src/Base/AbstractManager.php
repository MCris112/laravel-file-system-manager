<?php

namespace MCris112\FileSystemManager\Base;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractManager extends FileSystemManagerBase
{
    protected Model|null $parent;

    public function __construct(string $disk, Model|int|null $parent = null)
    {
        parent::__construct($disk);

        if(!$parent) return;

        if($parent instanceof Model)
        {
            $this->parent = $parent;
            return;
        }

        $this->parent = $this->find($parent);
    }

//    abstract public function content(?int $perPage = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    abstract public function search(string $name, ?int $perPage = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    abstract public function find(int $id): Model;

    public function parent(int|null $parent): static
    {
        return new static($this->disk, $parent);
    }

    abstract function delete(): void;
}
