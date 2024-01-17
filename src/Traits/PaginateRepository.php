<?php

namespace Olegario\PageSearchTraits\Traits;

trait PaginateRepository
{
    protected $paginate;

    public function paginate($perPage)
    {
        if (!$perPage) {
            return $this;
        }

        $this->perPage($perPage);
        $this->paginate = true;
        return $this;
    }
}