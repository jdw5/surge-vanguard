<?php

namespace Jdw5\Vanguard\Table;

use Conquest\Core\Primitive;
use Illuminate\Support\Collection;
use Jdw5\Vanguard\Actions\Concerns\HasActions;
use Jdw5\Vanguard\Table\Concerns\HasKey;
use Jdw5\Vanguard\Table\Concerns\HasMeta;
use Jdw5\Vanguard\Table\Contracts\Tables;
use Jdw5\Vanguard\Sorts\Concerns\HasSorts;
use Jdw5\Vanguard\Table\Concerns\Internal\HasBuilder;
use Jdw5\Vanguard\Columns\Concerns\HasColumns;
use Jdw5\Vanguard\Table\Concerns\HasPagination;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Table\Exceptions\InvalidKeyException;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Jdw5\Vanguard\Filters\Concerns\HasFilters;
use Jdw5\Vanguard\Pagination\Enums\PaginationType;
use Jdw5\Vanguard\Table\Concerns\HasExports;
use Jdw5\Vanguard\Table\Concerns\HasResource;
use Jdw5\Vanguard\Table\Concerns\HasSearch;

abstract class Table extends Primitive implements Tables
{
    use HasColumns;
    use HasActions;
    use HasPagination;
    use HasKey;
    use HasBuilder;
    use HasMeta;
    use HasSorts;
    use HasFilters;
    use HasResource;
    use HasExports;
    use HasSearch;

    protected Collection $records;

    public function __construct(
        EloquentBuilder|QueryBuilder $data = null,
        array $columns = null,
        array $actions = null,
        array $filters = null,
        array $sorts = null,
        array|string $search = null,
        array|int $pagination = null,
    )
    {
        $this->records = collect();
        $this->setResource($data);
        $this->setColumns($columns);
        $this->setActions($actions);
        $this->setFilters($filters);
        $this->setSorts($sorts);
        $this->setSearch($search);
        $this->setPagination($pagination);
    }
    
    /**
     * Create a new table instance.
     * 
     * @param EloquentBuilder|QueryBuilder $data
     * @return static
     */
    /** 
     * Todo: Alias for build, new
     * */
    public static function make(
        EloquentBuilder|QueryBuilder $resource = null,
        array $columns = null,
        array $actions = null,
        array $filters = null,
        array $sorts = null,
        array|string $search = null,
        array|int $pagination = null,
    ): static
    {
        return new static($resource, $columns, $actions, $filters, $sorts, $search, $pagination);
    }

    /**
     * Get the key for the table.
     * 
     * @return string
     * @throws InvalidKeyException
     * @return string
     */
    protected function getTableKey(): string 
    {
        try { 
            return $this->getKey();
        } 
        catch (InvalidKeyException $e) {
            return $this->findKeyColumn()?->getName() ?? throw $e;
        }
    }

    /**
     * Retrieve the table as an array
     * 
     * @return array
     */
    public function toArray(): array
    {
        $table = [
            'key' => $this->getTableKey(),
            'records' => $this->getRecords(),
            'columns' => $this->getTableColumns(),
            'meta' => $this->getMeta(),
            'sorts' => $this->getSorts(),
            'filters' => $this->getFilters(),
            'actions' => [
                'row' => $this->getRowActions(),
                'bulk' => $this->getBulkActions(),
                'page' => $this->getPageActions(),
                'default' => $this->getDefaultAction(),
            ],
            // 'allColumns'
            // 'pages' =>
        ];

        $pagination = $this->serializePagination();

        $preferences = $this->hasPreferences() ? ['preference_cols' => $this->getColumnsWithPreferences($this->getUncachedTableColumns())] : [];

        return array_merge($table, $pagination, $preferences);
    }

    /**
     * Retrieve the records from the table.
     * 
     * @return array
     */
    public function getRecords(): Collection
    {
        $this->process();
        return $this->records;
    }

    /**
     * Retrieve the metadata from the table.
     * 
     * @return array
     */
    public function getMeta(): array
    {
        $this->process();
        return $this->meta;
    }

    /**
     * Perform the pipeline by executing the query, applying the refinements and paginating the data.
     * 
     * @return void
     */
    private function retrieveData(): void
    {
        $builder = $this->getResource();

        $this->applyFilters($builder);
        $this->applySorts($builder);
        $this->applySearch($builder, $this->getSearchTerm(request()));

        [$records, $meta] = match ($this->getPaginateType()) {
            PaginationType::CURSOR => [
                $data = $builder->cursorPaginate(...array_values($this->getPagination()))->withQueryString(),
                $this->getCursorMeta($data)
            ],
            PaginationType::NONE => [
                $data = $builder->get(),
                $this->getCollectionMeta($data)
            ],
            default => [
                $data = $builder->paginate(...$this->getPagination()),
                $this->getPaginateMeta($data)
            ],
        };

        $this->setRecords($records);
        $this->setMeta($meta);

        // Handle the actions

        // Handle the columns
    }

    /**
     * Retrieve the records from the builder instance and generate metadata.
     * 
     * @param EloquentBuilder|QueryBuilder $builder
     * @return array
     */
    private function retrieveRecords(EloquentBuilder|QueryBuilder $builder): array
    {
        switch ($this->getPaginateType())
        {
            case 'cursor':
                $data = $this->getBuilder()->cursorPaginate(...\array_values($this->getPagination()))->withQueryString();
                return [$data->getCollection(), $this->generateCursorPaginatorMeta($data)];
            case 'get':
                $data = $builder->get();
                return [$data, $this->generateUnpaginatedMeta($data)];
            default:
                $data = $this->getBuilder()->paginate(...$this->getPagination())->withQueryString();
                return [$data->getCollection(), $this->generatePaginatorMeta($data)];
        }
    }
}