<?php

namespace Olegario\PageSearchTraits\Traits;

/**
 * Trait PaginateRepository
 *
 * This trait provides methods for enabling pagination in a repository class.
 */
trait PaginateRepository
{
    
    /**
     * @var bool $paginate Flag indicating whether pagination is enabled.
     */
    protected $paginate;

    /**
     * Enable pagination by setting the number of records to display per page.
     *
     * @param int $perPage The number of records per page.
     * @return $this The current instance of the repository with pagination enabled.
     */
    public function paginate($perPage): self
    {
        if (!$perPage) {
            return $this;
        }

        $this->perPage($perPage);
        $this->paginate = true;
        return $this;
    }
}