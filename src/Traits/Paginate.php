<?php

namespace Olegario\PageSearchTraits\Traits;

/**
 * Trait Paginate
 *
 * This trait provides pagination functionality for database queries.
 */
trait Paginate
{
    /**
     * @var int $perPage The number of records to display per page.
     */
    private $perPage;
    private int $totalPrepaidRegistration = 1000;
    

     /**
     * Get the current page index based on the 'page' query parameter.
     *
     * @return int The calculated current page index.
     * @throws \RuntimeException If 'page' query parameter is not an integer.
     */
    private function currentPage(): int
    {
        $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);

        if ($page === false) {
            throw new \RuntimeException("Invalid 'page' parameter. Must be an integer.");
        }

        $pageIndex = isset($page) ? $page : 1;
        return ($pageIndex - 1) * $this->perPage;
    }


    /**
     * Calculate the offset for the current page.
     *
     * @return int The offset for the current page.
     */
    private function offset(): int
    {
        if ($this->currentPage() > $this->totalRecord()) {
            return $this->totalRecord() - $this->perPage;
        }

        return $this->currentPage();
    }


    /**
     * Get the total number of records.
     *
     * @return int|null The total number of records.
     */
    private function totalRecord(): int|null
    {
        $totalRecord = $this->totalPrepaidRegistration;

        if (method_exists($this, 'countAll')) {
            $totalRecord = $this->countAll();
        }

        return $totalRecord;
    }


    /**
     * Set the number of records to display per page.
     *
     * @param int $perPage The number of records per page.
     * @throws \InvalidArgumentException If $perPage is not a positive integer.
     */
    private function perPage($perPage)
    {
        if (!is_int($perPage) || $perPage <= 0) {
            throw new \InvalidArgumentException("Invalid value for 'perPage'. Must be a positive integer.");
        }

        $this->perPage = $perPage;
    }


     /**
     * Generate the SQL pagination clause.
     *
     * @return string|null The SQL pagination clause or null if pagination is not enabled.
     * 
     */
    private function sqlPaginate(): string|null
    {
        if ($this->paginate) {
            return "limit {$this->perPage} offset {$this->offset()}";
        }

        return null;
    }
}
