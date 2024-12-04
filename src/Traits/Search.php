<?php

namespace Olegario\PageSearchTraits\Traits;

/**
 * Trait Search
 *
 * This trait provides methods for building SQL queries with search, order by, group by, and join functionalities.
 */
trait Search
{
    /**
     * #AND conditions
     *
     * @var array $searchFields An array of fields for AND conditions in the search.
     * @var array $searchValues An array of values corresponding to the AND conditions.
     * @var array $searchConditions An array of conditions (e.g., '=', '>', '<') for the AND conditions.
     */
    private $searchFields = [];
    private $searchValues = [];
    private $searchConditions = [];

    /**
     * #OR conditions
     *
     * @var array $searchFieldsOR An array of arrays containing fields for OR conditions in the search.
     * @var array $searchValuesOR An array of arrays containing values corresponding to the OR conditions.
     * @var array $searchConditionsOR An array of conditions for the OR conditions.
     */
    private array $searchFieldsOR = [];
    private array $searchValuesOR = [];
    private array $searchConditionsOR = [];


    /**
     * #ORDER BY conditions
     *
     * @var array $searchFieldOrder An array of fields for ORDER BY conditions.
     * @var array $sortFieldType An array of sorting types (e.g., ASC, DESC) for the ORDER BY conditions.
     */
    private array $searchFieldOrder = [];
    private array $sortFieldType;

    /**
     * #GROUP BY conditions
     *
     * @var array $groupFields An array of fields for GROUP BY conditions.
     */
    private array $groupFields;

    /**
     * #JOIN conditions
     *
     * @var array $searchType An array of join types (e.g., INNER JOIN, LEFT JOIN) for the join conditions.
     * @var array $searchBanco An array of tables for the join conditions.
     * @var array $searchSimilarField An array of fields for the join conditions.
     * @var array $searchSimilarFieldReverse An array of fields for the reverse join conditions.
     */
    private $searchType = [];
    private $searchBanco = [];
    private $searchSimilarField = [];
    private $searchSimilarFieldReverse = [];


    /**
     * Generate the SQL WHERE clause for AND conditions in the search.
     *
     * @return string The SQL WHERE clause for AND conditions.
     */
    private function sqlSearch(): string
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


    /**
     * Generate the SQL WHERE clause for OR conditions in the search.
     *
     * @return string The SQL WHERE clause for OR conditions.
     */
    private function sqlSearchOr(): string
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

    /**
     * Build the SQL WHERE clause for OR conditions in the search.
     *
     * @return string The SQL WHERE clause for OR conditions.
     */
    private function sqlSearchOrConditions(): string
    {
        if (!$this->searchOR || empty($this->searchFieldsOR)) {
            return '';
        }

        $conditions = [];
        foreach ($this->searchFieldsOR as $i => $fields) {
            foreach ($fields as $field) {
                $conditions[] = sprintf(
                    "%s %s '%s'",
                    $field,
                    $this->searchConditionsOR[$i],
                    $this->escapeValue($this->searchValuesOR[$i])
                );
            }
        }

        $where = $this->search === true ? ' AND ' : 'WHERE ';
        return $where . '(' . implode(" OR ", $conditions) . ')';
    }

    private function escapeValue(string $value): string
    {
        return addslashes($value); // Use um método de escape mais robusto no contexto real.
    }


    /**
     * Generate the SQL ORDER BY clause for order conditions in the search.
     *
     * @return string The SQL ORDER BY clause for order conditions.
     */
    private function sqlOrderBy(): string
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


    /**
     * Generate the SQL GROUP BY clause for group conditions in the search.
     *
     * @return string The SQL GROUP BY clause for group conditions.
     */
    private function sqlGroupBy(): string
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


    /**
     * Generate the SQL JOIN clause for join conditions.
     *
     * @return string The SQL JOIN clause for join conditions.
     */
    private function sqlJoind(): string
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