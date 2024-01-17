<?php

namespace Olegario\PageSearchTraits\Traits;

trait Search
{
    #AND
    private $searchFields = [];
    private $searchValues = [];
    private $searchConditions = [];

    #OR
    private array $searchFieldsOR = [];
    private array $searchValuesOR = [];
    private array $searchConditionsOR = [];

    #ORDER BY
    private array $searchFieldOrder = [];
    private array $sortFieldType;

    #GROUP BY
    private array $groupFields;

    #join
    private $searchType = [];
    private $searchBanco = [];
    private $searchSimilarField = [];
    private $searchSimilarFieldReverse = [];


    private function sqlSearch()
    {
        if ($this->search) {
            $conditions = [];
            foreach (array_filter($this->searchFields) as $i => $field) {
                $value = $this->searchValues[$i];
                $condition = $this->searchConditions[$i];
                $conditions[] = "{$field} {$condition} {$value}";
            }
            return "WHERE " . implode(" AND ", $conditions);
        }
        return '';
    }

    private function sqlSearchOr()
    {
        if ($this->searchOR) {
            $conditions = [];
            foreach ($this->searchFieldsOR as $i => $fields) {
                $conditionsGroup = [];
                foreach ($fields as $field) {
                    $conditionsGroup[] = "{$this->fieldMap[$field]} {$this->searchConditionsOR[$i]} {$this->searchValuesOR[$i]}";
                }
                $conditions[] = '(' . implode(" OR ", $conditionsGroup) . ')';
            }

            $where = $this->search === true ? ' AND ' : 'WHERE ';
            return $where . implode(" AND ", $conditions);
        }
        return '';
    }

    private function sqlOrderBy()
    {
        if ($this->searchOrder) {
            $conditions = [];
            foreach (array_filter($this->searchFieldOrder) as $i => $field) {
                $sortType = $this->sortFieldType[$i];
                $conditions[] = "{$field} {$sortType}";
            }

            return ' ORDER BY ' . implode(", ", $conditions);
        }
        return '';
    }

    private function sqlGroupBy()
    {
        if ($this->searchFieldGroup) {
            $conditions = [];
            foreach (array_filter($this->groupFields) as $i => $field) {
                $conditions[] = "{$this->fieldMap[$field]}";
            }

            return ' GROUP BY ' . implode(", ", $conditions);
        }
        return '';
    }

    private function sqlJoind()
    {
        if ($this->join) {
            $conditions = [];
            foreach (array_filter($this->searchType) as $i => $type) {
                $column = $this->searchSimilarField[$i];
                $columnReverse = $this->searchSimilarFieldReverse[$i];
                $tabela = $this->searchBanco[$i];
                $conditions[] = "{$type} {$tabela} on {$column} = {$columnReverse}";
            }
            return implode(" AND ", $conditions);
        }
        return '';
    }
}