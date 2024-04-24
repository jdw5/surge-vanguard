<?php

namespace Jdw5\Vanguard\Table;

use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasId;
use Jdw5\Vanguard\Concerns\HasActions;
use Illuminate\Database\Eloquent\Model;
use Jdw5\Vanguard\Table\Columns\Column;
use Jdw5\Vanguard\Table\Concerns\HasKey;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Table\Concerns\Applies;
use Jdw5\Vanguard\Table\Concerns\HasMeta;
use Jdw5\Vanguard\Table\Contracts\Tables;
use Jdw5\Vanguard\Concerns\HasRefinements;
use Jdw5\Vanguard\Table\Concerns\HasModel;
use Jdw5\Vanguard\Table\Concerns\HasDatabaseQuery;
use Jdw5\Vanguard\Table\Concerns\HasColumns;
use Jdw5\Vanguard\Table\Concerns\HasPagination;
use Jdw5\Vanguard\Table\Concerns\HasPreferences;
use Jdw5\Vanguard\Table\Concerns\HasProcess;
use Jdw5\Vanguard\Table\Concerns\HasRecords;
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
    use HasDatabaseQuery;
    use HasMeta;
    use HasPreferences;
    use Applies;
    use HasRecords;
    use HasProcess;

    public function __construct($data = null)
    {
        $this->setQuery($data);
    }

    /**
     * Create a new table instance.
     * 
     * @param Builder|null $data
     * @return static
     */
    public static function make($data = null): static
    {
        return new static($data);
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
     * Retrieve the table as an array
     */
    public function toArray(): array
    {
        return $this->jsonSerialize();
    }
    /**
     * Serialize the table to JSON.
     * 
     * @return array
     */
    public function jsonSerialize(): array
    {
        $core = [
            'meta' => $this->getMeta(),
            'rows' => $this->getRecords(),
            'cols' => $this->getTableColumns($this->hasPreferences(), $this->getPreferences())->values(),
            'refinements' => [
                'sorts' => $this->getSorts(),
                'filters' => $this->getFilters(),
            ],
            'actions' => [
                'inline' => $this->getInlineActions()->values(),
                'bulk' => $this->getBulkActions()->values(),
                'page' => $this->getPageActions()->values(),
                'default' => $this->getDefaultAction(),
            ],
            'recordKey' => $this->tableKey(),
        ];

        $pagination = $this->serializePagination();

        $preferences = $this->hasPreferences() ?
        [
            'preference_cols' => $this->getPreferenceColumns($this->getUncachedTableColumns())
        ] : [];

        return array_merge($core, $pagination, $preferences);
    }

    /**
     * Retrieve the records from the table.
     * 
     * @return array
     */
    public function getRecords(): array
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
        if (! $this->hasQuery()) { 
            $this->setQuery($this->defineQuery());
        }
        
        # Apply the default refiners
        $this->refineQuery($this->getRefinements());

        # Apply the sorts from sortable columns
        $this->refineQuery($this->getSortableColumns()->map->getSort()->filter());

        [$this->records, $this->meta] = $this->retrieveRecords($this->getQuery());

        // Free the query
        $this->freeQuery();
        
        // Apply the scopes
        // First scope is whether or not to drop non-cols
        // 
        // Next scope is whether or not to apply the column information

        // Next scope is to scope the actions to records

        // Next scope is to scope the routing of the actions
    }
}