<?php

namespace Conquest\Table;

use Conquest\Core\Primitive;
use Illuminate\Http\Request;
use Conquest\Table\Columns\BaseColumn;
use Illuminate\Support\Collection;
use Conquest\Table\Concerns\HasMeta;
use Conquest\Table\Contracts\Tables;
use Conquest\Table\Concerns\HasSearch;
use Conquest\Core\Concerns\RequiresKey;
use Conquest\Table\Concerns\HasExports;
use Conquest\Table\Concerns\HasRecords;
use Conquest\Table\Concerns\HasResource;
use Conquest\Table\Concerns\HasToggleKey;
use Illuminate\Database\Eloquent\Builder;
use Conquest\Table\Concerns\HasRememberKey;
use Conquest\Table\Sorts\Concerns\HasSorts;
use Conquest\Core\Exceptions\KeyDoesntExist;
use Conquest\Table\Actions\Concerns\HasActions;
use Conquest\Table\Columns\Concerns\HasColumns;
use Conquest\Table\Concerns\HasRememberDuration;
use Conquest\Table\Concerns\IsToggleable;
use Conquest\Table\Filters\Concerns\HasFilters;
use Conquest\Table\Pagination\Concerns\HasShowKey;
use Conquest\Table\Pagination\Enums\PaginationType;
use Conquest\Table\Pagination\Concerns\HasPagination;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Conquest\Table\Pagination\Concerns\HasPaginationKey;
use Conquest\Table\Pagination\Concerns\HasPaginationType;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

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
    use HasPaginationType;
    use HasPaginationKey;
    use HasShowKey;
    // use HasExports;
    use HasMeta;
    use HasRecords;
    use HasRememberKey;
    use HasRememberDuration;
    use HasToggleKey;
    use IsToggleable;

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
     * Alias for make
     */
    public static function build(
        Builder|QueryBuilder $resource = null,
        array $columns = null,
        array $actions = null,
        array $filters = null,
        array $sorts = null,
        array|string $search = null,
        array|int $pagination = null,
    ): static {
        return static::make(
            $resource,
            $columns,
            $actions,
            $filters,
            $sorts,
            $search,
            $pagination,
        );
    }

    /**
     * Get the key for the table.
     * 
     * @return string
     * @throws KeyDoesntExist
     * @return string
     */
    public function getTableKey(): string 
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
        $this->create();

        return [
            'key' => $this->getTableKey(),
            'records' => $this->getTableRecords(),
            'columns' => $this->getHeadingColumns(),
            'meta' => $this->getTableMeta(),
            'sorts' => $this->getSorts(),
            'filters' => $this->getFilters(),
            'actions' => [
                'row' => $this->getActions(),
                'bulk' => $this->getBulkActions(),
                'page' => $this->getPageActions(),
                // 'default' => $this->getDefaultAction(),
            ],
            'properties' => $this->getTableColumns(),
            'pagination' => $this->getPaginationOptions($this->getActivePagination()),
            'toggleKey' => $this->getToggleKey(),
            'showActions' => true, // Or SrOnly
        ];
    }

    /**
     * Retrieve the records from the table.
     * 
     * @return array
     */
    public function getTableRecords(): array
    {
        $this->create();
        return $this->getRecords();
    }

    /**
     * Retrieve the metadata from the table.
     * 
     * @return array
     */
    public function getTableMeta(): array
    {
        $this->create();
        return $this->getMeta();
    }

    /**
     * Perform the pipeline by executing the query, applying the refinements and paginating the data.
     * 
     * @return void
     */
    private function create(): void
    {
        // Records already retrieved and cached
        if ($this->hasRecords()) {
            return;
        }

        $builder = $this->getResource();
        $this->applyToggleability();
        $this->applyFilters($builder);
        $this->applySorts($builder, 
            array_map(fn ($column) => $column->getSort(), $this->getSortableColumns())
        );
        $this->applySearch($builder, $this->getSearchTerm(request()));
        // dd(DB::table('products')->where('best_seller', true)->getFrom());
        
        [$records, $meta] = match ($this->getPaginateType()) {
            PaginationType::CURSOR => [
                $data = $builder->cursorPaginate(
                    perPage: $this->getActivePagination(),
                    cursor: $this->getPageTerm(),
                )->withQueryString(),
                $this->getCursorMeta($data)
            ],
            PaginationType::NONE => [
                $data = $builder->get(),
                $this->getCollectionMeta($data)
            ],
            default => [
                $data = $builder->paginate(
                    perPage: $this->getActivePagination(),
                    pageName: $this->getPageTerm(),
                ),
                $this->getPaginateMeta($data)
            ],
        };

        $records = array_map(fn ($record) => array_reduce($this->getTableColumns(), 
            function ($filteredRecord, BaseColumn $column) use ($record) {
                $columnName = $column->getName();
                $filteredRecord[$columnName] = $column->apply($record[$columnName] ?? null);
                return $filteredRecord;
            }, []), $records instanceof Collection ? $records->toArray() : $records->items()
        );
        // For each record also apply the row action

        $this->setRecords($records);
        $this->setMeta($meta);
    }

    private function getActivePagination(): int
    {
        $count = $this->getPagination();
        if (is_int($count)) return $count;
        $query = request()->query($this->getShowKey());
        if (in_array($query, $count)) return $query;
        return $this->getDefaultPagination();
    }

    private function getToggledColumns(): array
    {
        $cols = request()->query($this->getToggleKey(), null);
        return (is_null($cols)) ? [] : explode(',', $cols);
    }

    private function applyToggleability(): void
    {
        // If it isn't toggleable then dont do anything
        if (!$this->isToggleable()) return;

        $cols = $this->getToggledColumns();

        if ($this->hasRememberKey() && empty($cols)) {
            // Use the remember key to get the columns
            $cols = json_decode(request()->cookie($this->getRememberKey(), []));
        }

        if (empty($cols)) {
            // If there are no columns, then set the default columns
            return;
        }

        foreach ($this->getTableColumns() as $column) {
            if (in_array($column->getName(), $cols)) $column->active(true);
            else $column->active(false);
        }

        if ($this->hasRememberKey()) {
            Cookie::queue($this->getRememberKey(), json_encode($cols), $this->getRememberDuration());
        }
    }
}
