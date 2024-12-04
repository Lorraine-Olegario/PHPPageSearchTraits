<?php

namespace Olegario\PageSearchTraits\Traits;

/**
 * Trait SearchRepository
 *
 * This trait provides methods for building search queries, including AND and OR conditions,
 * order by, group by, and join functionalities in a repository class.
 */
trait SearchRepository
{
    /**
     * @var bool $search Flag indicating whether AND conditions for search are enabled.
     * @var bool $searchOR Flag indicating whether OR conditions for search are enabled.
     * @var bool $searchOrder Flag indicating whether ORDER BY conditions are enabled.
     * @var bool $searchFieldGroup Flag indicating whether GROUP BY conditions are enabled.
     * @var bool $join Flag indicating whether JOIN conditions are enabled.
     */
    protected $search;
    protected $searchOR;
    protected $searchOrder;
    protected $searchFieldGroup;
    protected $join;


    /**
     * Add AND condition to the search query.
     *
     * @param string $field The field to search on.
     * @param string $condition The condition for the search (e.g., '=', '>', '<').
     * @param string|null $value The value to compare in the search.
     * @return $this The current instance of the repository with the added search condition.
     */
    public function search(string $field, string $condition, string|null $value): self
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


    /**
     * Add OR condition to the search query.
     *
     * @param array $field An array of fields to search on.
     * @param string $condition The condition for the search (e.g., '=', '>', '<').
     * @param string|null $value The value to compare in the search.
     * @return $this The current instance of the repository with the added OR search condition.
     */
    public function searchOR(array $field, string $condition, string|null $value): self
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


    /**
     * Add ORDER BY condition to the search query.
     *
     * @param string $field The field to order by.
     * @param string $order The sorting type (e.g., ASC, DESC).
     * @return $this The current instance of the repository with the added ORDER BY condition.
     */
    public function orderBy(string $field, string $order): self
    {
        if (!$order) {
            return $this;
        }

        $this->searchFieldOrder[] = $this->fieldMap[$field];
        $this->sortFieldType[] = $order;

        $this->searchOrder = true;
        return $this;
    }


    /**
     * Add JOIN condition to the search query.
     *
     * @param string $type The join type (e.g., INNER JOIN, LEFT JOIN).
     * @param string $database The table to join.
     * @param string $similarField The field in the current table for the join.
     * @param string $similarFieldReverse The field in the joined table for the join.
     * @return $this The current instance of the repository with the added JOIN condition.
     */
    public function join(string $type, string $database, string $similarField, string $similarFieldReverse): self
    {
        $this->searchType[] = $type;
        $this->searchBanco[] = $database;
        $this->searchSimilarField[] = $similarField;
        $this->searchSimilarFieldReverse[] = $similarFieldReverse;

        $this->join = true;
        return $this;
    }


    /**
     * Add GROUP BY condition to the search query.
     *
     * @param array $field An array of fields to group by.
     * @return $this The current instance of the repository with the added GROUP BY condition.
     */
    public function groupBy(array $field): self
    {
        if (!$field) {
            return $this;
        }

        $this->groupFields = $field;
        $this->searchFieldGroup = true;
        return $this;
    }


    /**
     * Add OR condition to the search query with different values for each field.
     *
     * @param array $fields An array of fields to search on.
     * @param array $conditions An array of conditions for the search (e.g., '=', '>', '<').
     * @param array $values An array of values to compare in the search.
     * @return $this The current instance of the repository with the added OR search condition.
     */
    public function searchORWithValues(array $fields, array $conditions, array $values): self
    {
        if (count($fields) !== count($conditions) || count($fields) !== count($values)) {
            return $this;
        }

        foreach ($fields as $index => $field) {
            if (empty($values[$index])) {
                continue;
            }

            $this->searchFieldsOR[] = [$this->fieldMap[$field]];
            $this->searchValuesOR[] = $values[$index];
            $this->searchConditionsOR[] = $conditions[$index];
        }

        $this->searchOR = true;
        return $this;
    }


    /**
     * Clears all values and criteria stored in the instance, allowing the same
     * instance to be reused for a new query without interference from previous searches.
     */
    public function reset(): self
    {
        $this->searchFields = [];
        $this->searchValues = [];
        $this->searchConditions = [];
        $this->search = false;

        $this->searchFieldsOR = [];
        $this->searchValuesOR = [];
        $this->searchConditionsOR = [];
        $this->searchOR = false;

        $this->searchFieldOrder = [];
        $this->sortFieldType = [];
        $this->searchOrder = false;

        $this->searchType = [];
        $this->searchBanco = [];
        $this->searchSimilarField = [];
        $this->searchSimilarFieldReverse = [];
        $this->join = false;

        $this->groupFields = [];
        $this->searchFieldGroup = false;
        return $this;
    }
}