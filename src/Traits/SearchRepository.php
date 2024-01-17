<?php

namespace Olegario\PageSearchTraits\Traits;

trait SearchRepository
{
    protected $search;
    protected $searchOR;
    protected $searchOrder;
    protected $searchFieldGroup;
    protected $join;

    public function search(string $field, string $condition, string|null $value)
    {
        if (!$value) {
            return $this;
        }

        $this->searchFields[] = $this->fieldMap[$field];
        $this->searchValues[] = $value;
        $this->searchConditions[] = $condition;

        $this->search = true;
        return $this;
    }

    public function searchOR(array $field, string $condition, string|null $value)
    {
        if (!$value) {
            return $this;
        }

        $this->searchFieldsOR[] = $field;
        $this->searchValuesOR[] = $value;
        $this->searchConditionsOR[] = $condition;

        $this->searchOR = true;
        return $this;
    }

    public function orderBy(string $field, string $order)
    {
        if (!$order) {
            return $this;
        }

        $this->searchFieldOrder[] = $this->fieldMap[$field];
        $this->sortFieldType[] = $order;

        $this->searchOrder = true;
        return $this;
    }

    public function join(string $type, string $database, string $similarField, string $similarFieldReverse)
    {

        $this->searchType[] = $type;
        $this->searchBanco[] = $database;
        $this->searchSimilarField[] = $similarField;
        $this->searchSimilarFieldReverse[] = $similarFieldReverse;

        $this->join = true;
        return $this;
    }

    public function groupBy(array $field)
    {
        if (!$field) {
            return $this;
        }

        $this->groupFields = $field;
        $this->searchFieldGroup = true;
        return $this;
    }
}