<?php

namespace Conquest\Table;

use Conquest\Core\Primitive;
use Illuminate\Support\Collection;
use Conquest\Table\Concerns\HasMeta;
use Conquest\Table\Contracts\Tables;
use Conquest\Table\Columns\BaseColumn;
use Conquest\Core\Concerns\RequiresKey;
use Conquest\Table\Concerns\HasRecords;
use Conquest\Table\Concerns\HasResource;
use Illuminate\Database\Eloquent\Builder;
use Conquest\Core\Exceptions\KeyDoesntExist;
use Conquest\Table\Concerns\Search\Searches;
use Conquest\Table\Actions\Concerns\HasActions;
use Conquest\Table\Columns\Concerns\HasColumns;
use Conquest\Table\Concerns\Remember\Remembers;
use Conquest\Table\Filters\Concerns\HasFilters;
use Conquest\Table\Pagination\Concerns\Paginates;
use Conquest\Table\Pagination\Enums\PaginationType;
use Conquest\Table\Sorts\Concerns\Sorts;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Table extends Primitive implements Tables
{
    use RequiresKey;
    use HasResource;
    use HasColumns;
    use HasActions;
    use HasFilters;
    use Sorts;
    use HasMeta;
    use HasRecords;
    use Remembers;
    use Paginates;
    use Searches;

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
     */
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
            'records' => $this->getTableRecords(),
            'headings' => $this->getHeadingColumns(),
            'meta' => $this->getTableMeta(),
            'sorts' => $this->getSorts(),
            'filters' => $this->getFilters(),
            'columns' => $this->getTableColumns(),
            'pagination' => $this->getPagination($this->usePerPage()),
            'actions' => [
                'inline' => $this->getInlineActions(),
                'bulk' => $this->getBulkActions(),
                'page' => $this->getPageActions(),
                'default' => $this->getDefaultAction(),
            ],
            'keys' => [
                'id' => $this->getTableKey(),
                'sort' => $this->getSortKey(),
                'order' => $this->getOrderKey(),
                'show' => $this->getShowKey(),
                'post' => $this->getActionRoute(),
                'search' => $this->getSearchKey(),
                'toggle' => $this->getToggleKey(),
            ]
        ];
    }

    /**
     * Retrieve the records from the table.
     * 
     * @return Collection
     */
    public function getTableRecords(): Collection
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
    protected function create(): void
    {
        if ($this->hasRecords()) return;

        $builder = $this->getResource();
        // $this->applyToggleability();
        $this->filter($builder);
        $this->sort($builder, $this->combinedSorts());
        $this->search($builder, $this->combinedSearch());
        
        [$records, $meta] = match ($this->getPaginateType()) {
            PaginationType::CURSOR => [
                $data = $builder->cursorPaginate(
                    perPage: $this->usePerPage(),
                    cursorName: $this->getPageName(),
                )->withQueryString(),
                $this->getCursorMeta($data)
            ],
            PaginationType::NONE => [
                $data = $builder->get(),
                $this->getCollectionMeta($data)
            ],
            default => [
                $data = $builder->paginate(
                    perPage: $this->usePerPage(),
                    pageName: $this->getPageName(),
                )->withQueryString(),
                $this->getPaginateMeta($data)
            ],
        };

        $records = collect($records instanceof Collection ? $records : $records->items())
            ->map(function ($record) {
                return $this->getTableColumns()->reduce(function ($filteredRecord, BaseColumn $column) use ($record) {
                    $columnName = $column->getName();
                    $filteredRecord[$columnName] = $column->apply($record[$columnName] ?? null);
                    return $filteredRecord;
                }, []);
            });
            
        $this->setRecords($records);
        $this->setMeta($meta);
    }

    public function combinedSearch(): array
    {
        return array_merge($this->getSearch(), $this->getSearchableColumns()->toArray());
    }

    public function combinedSorts(): array
    {
        return array_merge($this->getSorts(), $this->getSortableColumns()->map(fn ($column) => $column->getSort())->toArray());
    }

    public function toggle(): void
    {

        // $this->applyToggleability();
    }

    // private function getToggledColumns(): array
    // {
    //     $cols = request()->query($this->getToggleKey(), null);
    //     return (is_null($cols)) ? [] : explode(',', $cols);
    // }

    // private function applyToggleability(): void
    // {
    //     // If it isn't toggleable then dont do anything
    //     if (!$this->isToggleable()) return;

    //     $cols = $this->getToggledColumns();

    //     if ($this->hasRememberKey() && empty($cols)) {
    //         // Use the remember key to get the columns
    //         $cols = json_decode(request()->cookie($this->getRememberKey(), []));
    //     }

    //     if (empty($cols)) {
    //         // If there are no columns, then set the default columns
    //         return;
    //     }

    //     foreach ($this->getTableColumns() as $column) {
    //         if (in_array($column->getName(), $cols)) $column->active(true);
    //         else $column->active(false);
    //     }

    //     if ($this->hasRememberKey()) {
    //         Cookie::queue($this->getRememberKey(), json_encode($cols), $this->getRememberDuration());
    //     }
    // }
}
