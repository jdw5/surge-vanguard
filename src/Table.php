<?php

namespace Conquest\Table;

use Conquest\Core\Concerns\RequiresKey;
use Conquest\Core\Exceptions\MissingRequiredAttributeException;
use Conquest\Core\Primitive;
use Conquest\Table\Actions\Concerns\HasActions;
use Conquest\Table\Columns\BaseColumn;
use Conquest\Table\Columns\Concerns\HasColumns;
use Conquest\Table\Concerns\HasMeta;
use Conquest\Table\Concerns\HasRecords;
use Conquest\Table\Concerns\HasResource;
use Conquest\Table\Concerns\Remember\Remembers;
use Conquest\Table\Concerns\Search\Searches;
use Conquest\Table\Contracts\Tables;
use Conquest\Table\Filters\Concerns\HasFilters;
use Conquest\Table\Pagination\Concerns\Paginates;
use Conquest\Table\Pagination\Enums\PaginationType;
use Conquest\Table\Sorts\BaseSort;
use Conquest\Table\Sorts\Concerns\Sorts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

class Table extends Primitive implements Tables
{
    use HasActions;
    use HasColumns;
    use HasFilters;
    use HasMeta;
    use HasRecords;
    use HasResource;
    use Paginates;
    use Remembers;
    use RequiresKey;
    use Searches;
    use Sorts;

    public function __construct(
        Builder|QueryBuilder|null $resource = null,
        ?array $columns = null,
        ?array $actions = null,
        ?array $filters = null,
        ?array $sorts = null,
        array|string|null $search = null,
        array|int|null $pagination = null,
    ) {
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
        Builder|QueryBuilder|null $resource = null,
        ?array $columns = null,
        ?array $actions = null,
        ?array $filters = null,
        ?array $sorts = null,
        array|string|null $search = null,
        array|int|null $pagination = null,
    ): static {
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
        Builder|QueryBuilder|null $resource = null,
        ?array $columns = null,
        ?array $actions = null,
        ?array $filters = null,
        ?array $sorts = null,
        array|string|null $search = null,
        array|int|null $pagination = null,
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
     * @throws MissingRequiredAttributeException
     */
    public function getTableKey(): string
    {
        try {
            return $this->getKey();
        } catch (MissingRequiredAttributeException $e) {
            return $this->getKeyColumn()?->getName() ?? throw $e;
        }
    }

    /**
     * Retrieve the table as an array
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
            ],
        ];
    }

    public function getTableRecords(): Collection
    {
        $this->create();

        return $this->getRecords();
    }

    /**
     * @return array<string, mixed>
     */
    public function getTableMeta(): array
    {
        $this->create();

        return $this->getMeta();
    }

    protected function create(): void
    {
        if ($this->hasRecords()) {
            return;
        }

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
                $this->getCursorMeta($data),
            ],
            PaginationType::NONE => [
                $data = $builder->get(),
                $this->getCollectionMeta($data),
            ],
            default => [
                $data = $builder->paginate(
                    perPage: $this->usePerPage(),
                    pageName: $this->getPageName(),
                )->withQueryString(),
                $this->getPaginateMeta($data),
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

    /**
     * @return array<string>
     */
    public function combinedSearch(): array
    {
        return array_merge($this->getSearch(), $this->getSearchableColumns()->toArray());
    }

    /**
     * @return array<BaseSort>
     */
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
