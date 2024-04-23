<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Jdw5\Vanguard\Table\Actions\BaseAction;
use Jdw5\Vanguard\Table\Columns\Column;

/**
 * Trait HasConfiguration
 * 
 * Store core configuration parameters for the table
 */
trait Applies
{
    protected $applyColumns = true;
    protected $applyActionDependency = true;
    protected $applyActionRouting = true;

    public function applyCases()
    {
        return $this->applyColumns + $this->applyActionDependency * 2 + $this->applyActionRouting * 4;
    }

    /**
     * Apply a column transformation to a single record
     * 
     * @param Column $column
     * @param mixed $record
     * @return mixed
     */
    protected function applyColumn(Column $column, mixed $record): mixed
    {
        $name = $column->getName();
        // dd($record, $column->getName());
        $field = $record instanceof Model ? $record[$name] : $record->{$name};
        return empty($field) ? $column->getFallback() : $column->transformUsing($field);
    }

    /**
     * Add actions to a record based on conditional logic
     * 
     * @param mixed $record
     * @param Collection $inlineActions
     * @return Collection
     */
    protected function applyActionConditional(mixed $record, Collection $inlineActions): Collection
    {
        return $inlineActions->filter(function (BaseAction $action) use ($record) {
            return $action->evaluateConditional($record);
        });
    }

    /**
     * Add routing to the actions based on the record
     * 
     * @param mixed $record
     * @param Collection $inlineActions
     * @return Collection
     */
    protected function applyRouting(mixed $record, Collection $inlineActions): Collection
    {
        return $inlineActions->map(function (BaseAction $action) use ($record) {
            return array_merge($action->jsonSerialize(), $action->resolveEndpoint($record) ?? []);
        });
    }

    /**
     * Apply columns to the record (inplace)
     * 
     * @param array $record
     * @param Collection $cols
     * @return void
     */
    public function applyColumns(mixed &$record, Collection $cols): void
    {
        foreach ($cols->toArray() as $col) {
            $name = $col->getName();
            $record[$name] = $this->applyColumn($col, $record);
        }
    }

    /**
     * Apply action dependency to the record (inplace)
     * 
     * @param array $record
     * @param Collection $inlineActions
     * @return void
     */
    public function applyActionDependency(mixed &$record, Collection $inlineActions): void
    {
        $record['actions'] = $this->applyActionConditional($record, $inlineActions);
    }

    /**
     * Apply the action routing to the record (inplace)
     * 
     * @param array $record
     * @param Collection $inlineActions
     * @return void
     */
    public function applyActionRouting(mixed &$record, Collection $inlineActions): void
    {
        $record['actions'] = $this->applyRouting($record, $inlineActions);
    }
}

