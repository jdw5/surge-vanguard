<?php

namespace Jdw5\Vanguard\Table;

use Jdw5\Vanguard\Primitive;
use Illuminate\Support\Collection;
use Jdw5\Vanguard\Concerns\HasActions;
use Illuminate\Database\Eloquent\Model;
use Jdw5\Vanguard\Table\Concerns\HasKey;
use Jdw5\Vanguard\Table\Concerns\HasMeta;
use Jdw5\Vanguard\Table\Contracts\Tables;
use Jdw5\Vanguard\Concerns\HasRefinements;
use Jdw5\Vanguard\Table\Concerns\HasModel;
use Jdw5\Vanguard\Table\Concerns\HasScopes;
use Jdw5\Vanguard\Table\Concerns\HasBuilder;
use Jdw5\Vanguard\Table\Concerns\HasColumns;
use Jdw5\Vanguard\Table\Concerns\HasProcess;
use Jdw5\Vanguard\Table\Concerns\HasRecords;
use Jdw5\Vanguard\Table\Concerns\HasPagination;
use Jdw5\Vanguard\Table\Concerns\HasPreferences;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Table\Exceptions\InvalidKeyException;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

abstract class Table extends Primitive implements Tables
{
    use HasColumns;
    use HasActions;
    use HasModel;
    use HasPagination;
    use HasRefinements;
    use HasKey;
    use HasBuilder;
    use HasMeta;
    use HasPreferences;
    use HasScopes;
    use HasRecords;
    use HasProcess;

    // HasExport
    // HasFilters

    public function __construct($data = null)
    {
        $this->setBuilder($data);
    }
    
    /**
     * Create a new table instance.
     * 
     * @param EloquentBuilder|QueryBuilder|null $data
     * @return static
     */
    public static function make(
        EloquentBuilder|QueryBuilder $data = null,        
    ): static
    {
        return new static($data);
    }

    /** 
     * Create a new table instance (alias)
     * 
     * @param EloquentBuilder|QueryBuilder|null $data
     */
    public static function from($data): static
    {
        return static::make($data);
    }

    /**
     * Create a new table instance from a model.
     * 
     * @param Model $model
     * @return static
     */
    public static function fromModel(Model $model): static
    {
        return static::make($model->newQuery());
    }

    /**
     * Get the key for the table.
     * 
     * @return string
     * @throws InvalidKeyException
     * @return string
     */
    protected function tableKey(): string 
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
            'meta' => $this->getMeta(),
            'rows' => $this->getRecords(),
            'cols' => $this->getTableColumns(),
            'refinements' => [
                'sorts' => $this->getSorts(),
                'filters' => $this->getFilters(),
            ],
            'actions' => [
                'inline' => $this->getInlineActions(),
                'bulk' => $this->getBulkActions(),
                'page' => $this->getPageActions(),
                'default' => $this->getDefaultAction(),
            ],
            'recordKey' => $this->tableKey(),
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
}