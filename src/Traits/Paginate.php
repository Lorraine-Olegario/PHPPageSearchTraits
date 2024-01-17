<?php

namespace Olegario\PageSearchTraits\Traits;

trait Paginate
{
    private $perPage;

    private function currentPage()
    {
        $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
        $pageIndex = isset($page) ? $page : 1;
        return ($pageIndex - 1) * $this->perPage;
    }

    private function offset()
    {
        if ($this->currentPage() > $this->totalRecord()) {
            return $this->totalRecord() - $this->perPage;
        }

        return $this->currentPage();
    }

    private function totalRecord()
    {
        $totalRecord = $this->countAll();
        return $totalRecord;
    }

    private function perPage($perPage)
    {
        $this->perPage = $perPage;
    }

    private function sqlPaginate()
    {
        if ($this->paginate) {
            return "limit {$this->perPage} offset {$this->offset()}";
        }
    }
}
