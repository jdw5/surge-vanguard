<?php

namespace Jdw5\Vanguard\Table;

use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasId;
use Illuminate\Support\Collection;
use Jdw5\Vanguard\Concerns\HasActions;
use Illuminate\Database\Eloquent\Model;
use Jdw5\Vanguard\Table\Concerns\HasKey;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Table\Concerns\HasMeta;
use Jdw5\Vanguard\Table\Contracts\Tables;
use Jdw5\Vanguard\Concerns\HasRefinements;
use Jdw5\Vanguard\Table\Concerns\HasModel;
use Jdw5\Vanguard\Table\Concerns\HasScopes;
use Jdw5\Vanguard\Table\Concerns\HasColumns;
use Jdw5\Vanguard\Table\Concerns\HasProcess;
use Jdw5\Vanguard\Table\Concerns\HasRecords;
use Jdw5\Vanguard\Table\Concerns\HasPagination;
use Jdw5\Vanguard\Table\Concerns\HasPreferences;
use Jdw5\Vanguard\Table\Concerns\HasBuilder;
use Jdw5\Vanguard\Table\Exceptions\InvalidKeyException;

abstract class Table extends Primitive implements Tables
{
    use HasColumns;
    use HasActions;
    use HasId;
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

    public function __construct($data = null)
    {
        $this->setQuery($data);
    }
    
    /**
     * Create a new table instance.
     * 
     * @param EloquentBuilder|QueryBuilder|null $data
     * @return static
     */
    public static function make($data = null): static
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
    public function tablePipeline(): void
    {
        if (! $this->hasQuery()) $this->setQuery($this->defineQuery());
        
        $this->refineQuery($this->getRefinements());

        $this->refineQuery($this->getSortableColumns()->map->getSort()->filter());

        [$this->records, $this->meta] = $this->retrieveRecords($this->getQuery());

        $this->freeQuery();

        $this->applyScopes($this->records, $this->getTableColumns(), $this->getInlineActions());
    }
}