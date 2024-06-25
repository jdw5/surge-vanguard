<?php

namespace Jdw5\Vanguard\Table;

use Jdw5\Vanguard\Primitive;
use Illuminate\Support\Collection;
use Jdw5\Vanguard\Actions\Concerns\HasActions;
use Illuminate\Database\Eloquent\Model;
use Jdw5\Vanguard\Table\Concerns\HasKey;
use Jdw5\Vanguard\Table\Concerns\HasMeta;
use Jdw5\Vanguard\Table\Contracts\Tables;
use Jdw5\Vanguard\Concerns\HasRefinements;
use Jdw5\Vanguard\Sorts\Concerns\HasSorts;
use Jdw5\Vanguard\Table\Concerns\HasModel;
use Jdw5\Vanguard\Table\Concerns\HasScopes;
use Jdw5\Vanguard\Table\Concerns\Internal\HasBuilder;
use Jdw5\Vanguard\Columns\Concerns\HasColumns;
use Jdw5\Vanguard\Table\Concerns\HasProcess;
use Jdw5\Vanguard\Table\Concerns\HasRecords;
use Jdw5\Vanguard\Table\Concerns\HasPagination;
use Jdw5\Vanguard\Table\Concerns\HasPreferences;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Table\Exceptions\InvalidKeyException;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\Request;
use Jdw5\Vanguard\Actions\BaseAction;
use Jdw5\Vanguard\Filters\Concerns\HasFilters;
use Jdw5\Vanguard\Table\Concerns\HasExports;
use Jdw5\Vanguard\Table\Concerns\HasResource;

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
        array $exports = null
    ): static
    {
        return new static($resource, $columns, $actions, $filters, $sorts, $search, $pagination, $exports);
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
     * Serialize the table to JSON.
     * 
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
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
    public function pipeline(): void
    {
        if (! $this->hasBuilder()) $this->setBuilder($this->defineQuery());
        
        $this->refineBuilder($this->getRefinements());

        $this->refineBuilder($this->getSortableColumns()->map->getSort()->filter());

        [$this->records, $this->meta] = $this->retrieveRecords($this->getBuilder());

        $this->freeBuilder();

        $this->applyScopes($this->records, $this->getTableColumns(), $this->getInlineActions());
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

    public static function handle(Request $request): mixed
    {
        [$type, $name] = explode(':', $request->input('name'));
        // If either doesn't exist, then the request is invalid
        if (!$type || !$name) abort(400);

        return match ($type) {
            'action' => static::handleAction($request, $name),
            'export' => static::handleExport($request, $name),
            default => null
        };
        /**
         * return Table::handle($request);
         * Accepts a request, pulls out the values
         * Find the first action OR export which matches the `type:name` and `httpMethod`
         * 
         * If anything is found, check the permissions -> authorize
         * If authorize fails, abort(403)
         * 
         * Export:
         * create the export for the table
         * -> frontend handles it as axios NOT inertia
         * 
         * Actions format depends on whether it's bulk or row
         */
    }

    private static function handleAction(Request $request, string $name): mixed
    {
        // Find the action which has the name and method the same as the request
        $action = static::findAction($name, $request->method(), $request->get('type'));

        if (!$action) return;

        return $action->handle($request);
    }

    private static function findAction(string $name, string $method, string $type = null): BaseAction|null
    {
        if (is_null($type)) $type = 'row';

        return static::getActions()->first(fn($action) => $action->getName() === $name 
            && $action->getMethod() === $method 
            && $action->getType() === $type
        );
    }

    private static function handleExport(Request $request, string $name): mixed
    {
        $export = static::findExport($name, $request->method());

        if (!$export) return;
        $export->handle($request);
        return $export->after();
    }
}