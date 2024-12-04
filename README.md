# PageSearchTraits

Introducing PageSearchTraits, a library containing specialized pagination and search traits to improve the flexibility and efficiency of data access in PHP projects. Simplify your repository operations with robust functionality and easy integration.

The class uses Trait to extend its query capabilities. The added methods allow complex queries to be built, taking advantage of concepts such as field mapping and search operators.

## Installation:

```bash
composer require olegario/page-search-traits
```

## Examples

```bash
class testRepository
{
    private PDO $conection; 
    use Paginate;
    use PaginateRepository;
    use Search;
    use SearchRepository;

    protected array $fieldMap = [
        'status'       => 'teste_stts',
        'description'  => 'teste_dscrc',
        'number'       => 'teste_nmr',
    ];

    public function all(): array
    {
        $select = $this->conection->query("
             SELECT * FROM tabela 
                {$this->sqlJoind()} 
                {$this->sqlSearch()}
                {$this->sqlSearchOr()}
                {$this->sqlSearchOrConditions()}
                {$this->sqlOrderBy()} 
                {$this->sqlGroupBy()} 
                {$this->sqlPaginate()}
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
}
```

The $fieldMap array plays a crucial role when mapping database table fields. It is used to ensure a consistent and safe approach when referencing fields, avoiding the direct use of field names and promoting a mapping practice.
```bash
protected array $fieldMap = [
        'status'       => 'teste_stts',
        'description'  => 'teste_dscrc',
        'number'       => 'teste_nmr',
];
```   
Example serach usage: search by specific field using and, for each search field a new line must be added. Performs a detailed search on a specific field using operators such as 'like', '=', or 'in'.

```bash
$testRepository->search('number','like','%$searchNumber%');
$testRepository->search('number', '=', $searchNumber);
$testRepository->search('number', '<>', $searchNumber);
```

Example using serachOr: search for one or more specific fields using the or operator. 
```bash
$testRepository->searchOR(['number', 'description'], 'like', $searchValue);
$testRepository->searchOR(['test'], '=', $searchValue);
 ```
 
Example using sqlSearchOrConditions: search condition with different values for each field.
```bash
$testRepository->searchORWithValues(
    ['number', 'description', 'number'],
    ['like', '=', '>'],
    ['%3%', 'report', '454']
)
 ```

Example of using orderBy
```bash
$testRepository->orderBy('number', 'asc');
```

Example of using groupBy: following the order of the fields.
```bash
$testRepository->groupBy(['status', 'commission']);
```

Example of using join.
```bash
$testRepository->join('INNER', 'commission_table', 'id', 'commission_id');
```

Example clears all values and criteria stored in the instance, allowing the same instance to be reused for a new query without interference from previous searches.
```bash
$testRepository->reset()
```
