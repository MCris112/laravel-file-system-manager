<?php

namespace MCris112\FileSystemManager\Base;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractManager extends FileSystemManagerBase
{
    protected Model|null $parent = null;

    public function __construct(string $disk, Model|int|null $parent = null)
    {
        parent::__construct($disk);

        $this->parent($parent);
    }

//    abstract public function content(?int $perPage = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    abstract public function search(string $name, ?int $perPage = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    abstract public function find(int $id): Model;

    public function parent(Model|int|null $parent): static
    {
        if(!$parent)
        {
            $this->parent = $parent;
            return $this;
        }

        if($parent instanceof Model)
        {
            $this->parent = $parent;
            return $this;
        }

        $this->parent = $this->find($parent);
        return $this;
    }

    abstract function delete(): void;

    public function get()
    {
        return $this->parent;
    }
}
