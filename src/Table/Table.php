<?php

namespace Jdw5\SurgeVanguard\Table;

use Jdw5\SurgeVanguard\Primitive;
use Jdw5\SurgeVanguard\Concerns\HasId;
use Jdw5\SurgeVanguard\Concerns\HasScope;
use Jdw5\SurgeVanguard\Concerns\HasActions;
use Jdw5\SurgeVanguard\Table\Concerns\HasKey;
use Jdw5\SurgeVanguard\Table\Contracts\Tables;
use Jdw5\SurgeVanguard\Concerns\HasRefinements;
use Jdw5\SurgeVanguard\Table\Concerns\HasColumns;
use Jdw5\SurgeVanguard\Table\Concerns\HasRecords;
use Jdw5\SurgeVanguard\Eloquent\Concerns\HasModel;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\SurgeVanguard\Table\Concerns\HasPagination;
use Illuminate\Database\Eloquent\Collection;
use Jdw5\SurgeVanguard\Table\Exceptions\InvalidKeyException;

abstract class Table extends Primitive implements Tables
{
    use HasColumns,
        HasActions,
        HasScope,
        HasId,
        HasModel,
        HasPagination,
        HasRecords,
        HasRefinements,
        HasKey;

    public function __construct(?Builder $data = null)
    {
        $this->query($data);
    }

    public static function make(?Builder $data = null): static
    {
        return new static($data);
    }

    public function tableKey(): string 
    {
        try { 
            return $this->getKey();
        } catch (InvalidKeyException $e) {
            return $this->findKeyColumn()?->getName() ?? throw $e;
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'meta' => $this->getMeta(),
            'rows' => $this->getRecords(),
            'cols' => $this->getTableColumns()->values(),
            'refinements' => [
                'sorts' => $this->getSorts()->values(),
                'filters' => $this->getFilters()->values(),
            ],
            'actions' => [
                'inline' => $this->getInlineActions()->values(),
                'bulk' => $this->getBulkActions()->values(),
                'page' => $this->getPageActions(),
                'default' => $this->getDefaultAction(),
            ],
            'recordKey' => $this->tableKey(),
        ];
    }   
}