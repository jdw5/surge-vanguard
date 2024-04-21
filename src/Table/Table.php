<?php

namespace Jdw5\Vanguard\Table;

use Jdw5\Vanguard\Primitive;
use Jdw5\Vanguard\Concerns\HasId;
use Jdw5\Vanguard\Concerns\HasActions;
use Jdw5\Vanguard\Table\Columns\Column;
use Jdw5\Vanguard\Table\Concerns\HasKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Jdw5\Vanguard\Table\Concerns\HasMeta;
use Jdw5\Vanguard\Table\Contracts\Tables;
use Jdw5\Vanguard\Concerns\HasRefinements;
use Jdw5\Vanguard\Table\Concerns\HasModel;
use Jdw5\Vanguard\Table\Concerns\HasQuery;
use Jdw5\Vanguard\Table\Concerns\HasColumns;
use Jdw5\Vanguard\Table\Concerns\HasPagination;
use Jdw5\Vanguard\Table\Concerns\HasPreferences;
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
    use HasQuery;
    use HasMeta;
    use HasPreferences;

    private mixed $cachedMeta = null;
    private mixed $cachedData = null;
    protected $applyColumns = true;

    public function __construct(mixed $data = null)
    {
        $this->query($data);
    }

    /**
     * Create a new table instance.
     * 
     * @param Builder|null $data
     * @return static
     */
    public static function make(mixed $data = null): static
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

        $pagination = $this->hasDynamicPagination() ? 
        [
            'paging_options' => [
                'options' => $this->getPaginationOptions(),
                'key' => $this->showKey()
            ]
        ] : [];

        $preferences = $this->hasPreferences() ?
        [
            'preference_cols' => $this->getPreferenceColumns($this->getUncachedTableColumns())
        ] : [];

        return array_merge($core, $pagination, $preferences);
    }

    /**
     * Retrieve the records from the table.
     * 
     * @return mixed
     */
    public function getRecords(): mixed
    {
        return $cachedData ??= $this->pipelineWithData();
    }

    public function getFirstRecord(): mixed
    {
        return $this->getRecords()->first();
    }

    /**
     * 
     */
    protected function beforeFetch(Builder|QueryBuilder $query) 
    {
        return $query;
    }

    /**
     * Retrieve the metadata from the table.
     * 
     * @return array
     */
    public function getMeta(): array
    {
        return $cachedMeta ??= $this->pipelineWithMeta();
    }

    /**
     * Perform the pipeline and retrieve the data
     * 
     * @return mixed
     */
    public function pipelineWithData(): mixed 
    {
        $this->pipeline();
        return $this->cachedData;
    }

    /**
     * Perform the pipeline and retrieve the metadata
     * 
     * @return mixed
     */
    public function pipelineWithMeta(): mixed
    {
        $this->pipeline();
        return $this->cachedMeta;
    }

    /**
     * Perform the pipeline by executing the query, applying the refinements and paginating the data.
     * 
     * @return void
     */
    protected function pipeline(): void
    {
        if (!\is_null($this->cachedData)) return;

        if (!$this->hasQuery()) { 
            $this->query = $this->defineQuery();
        }
        
        $this->query($this->query
            ->withRefinements($this->getRefinements())
            ->withRefinements($this->getSortableColumns()->map
                ->getSort()
                ->filter()
                ->toArray()
            )
        );

        // Check if the afterRetrieval method exists
        if (\method_exists($this, 'afterRetrieval')) {
            $this->query = $this->query->afterRetrieval([$this, 'afterRetrieval']);
        }
        
        // Perform the pagination/get now the collection is retrieval
        switch ($this->getPaginateType())
        {
            case 'cursor':
                $cursorPaginatedData = $this->query->cursorPaginate(...\array_values($this->getPagination()))->withQueryString();
                $this->cachedData = $cursorPaginatedData->items();
                $this->cachedMeta = $this->generateCursorPaginatorMeta($cursorPaginatedData);
                break;
            case 'get':
                $this->cachedData = $this->query->get();
                $this->cachedMeta = $this->generateUnpaginatedMeta($this->cachedData);
                break;
            default:
                $paginatedData = $this->query->paginate(...$this->getPagination())->withQueryString();
                $this->cachedData = $paginatedData->items();
                $this->cachedMeta = $this->generatePaginatorMeta($paginatedData);
                break;
        }

        if ($this->applyColumns)
        {
            $this->cachedData = collect($this->cachedData)->map(function ($row) {
                return $this->getTableColumns()->reduce(function ($carry, Column $column) use ($row) {
                    $name = $column->getName();
                    if ($row instanceof Model) {
                        $carry[$name] = empty($row[$name]) ? $column->getFallback() : $column->transformUsing($row[$name]);
                    } else {
                        $carry[$name] = empty($row->{$name}) ? $column->getFallback() : $column->transformUsing($row->{$name});
                    }
                    return $carry;
                }, []);
            });      
        }
    }
}