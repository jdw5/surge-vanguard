<?php

namespace Conquest\Table;

use Conquest\Core\Primitive;
use Conquest\Core\Exceptions\KeyDoesntExist;
use Conquest\Core\Concerns\RequiresKey;
use Conquest\Table\Concerns\HasMeta;
use Conquest\Table\Contracts\Tables;
use Conquest\Table\Concerns\HasSearch;
use Conquest\Table\Concerns\HasExports;
use Conquest\Table\Concerns\HasRecords;
use Conquest\Table\Concerns\HasResource;
use Conquest\Table\Concerns\HasPagination;
use Conquest\Table\Sorts\Concerns\HasSorts;
use Conquest\Table\Actions\Concerns\HasActions;
use Conquest\Table\Columns\Concerns\HasColumns;
use Conquest\Table\Filters\Concerns\HasFilters;
use Conquest\Table\Pagination\Enums\PaginationType;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

abstract class Table extends Primitive implements Tables
{
    use RequiresKey;
    use HasResource;
    use HasColumns;
    use HasActions;
    use HasFilters;
    use HasSorts;
    use HasSearch;
    use HasPagination;
    // use HasExports;
    use HasMeta;
    use HasRecords;

    protected Collection $records;

    public function __construct(
        Builder|QueryBuilder $resource = null,
        array $columns = null,
        array $actions = null,
        array $filters = null,
        array $sorts = null,
        array|string $search = null,
        array|int $pagination = null,
    )
    {
        $this->setRecords(collect());
        $this->setResource($resource);
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
     * @param Builder|QueryBuilder $data
     * @return static
     */
    /** 
     * Todo: Alias for build, new
     * */
    public static function make(
        Builder|QueryBuilder $resource = null,
        array $columns = null,
        array $actions = null,
        array $filters = null,
        array $sorts = null,
        array|string $search = null,
        array|int $pagination = null,
    ): static
    {
        return resolve(static::class, compact(
            'resource',
            'columns',
            'actions',
            'filters',
            'sorts',
            'search',
            'pagination',
        ));
    }

    /**
     * Get the key for the table.
     * 
     * @return string
     * @throws KeyDoesntExist
     * @return string
     */
    protected function getTableKey(): string 
    {
        try { 
            return $this->getKey();
        } 
        catch (KeyDoesntExist $e) {
            return $this->getKeyColumn()?->getName() ?? throw $e;
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
    public function getTableRecords(): Collection
    {
        if (!$this->hasRecords()) {
            $this->retrieveData();
        }
        return $this->records;
    }

    /**
     * Retrieve the metadata from the table.
     * 
     * @return array
     */
    public function getTableMeta(): array
    {
        if (!$this->hasMeta()) {
            $this->retrieveData();
        }
        return $this->getMeta();
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
        // $this->applyColumnSorts($builder);
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
        // $this->buildActions();

        // Handle the columns
        // $this->buildColumns();
    }
}