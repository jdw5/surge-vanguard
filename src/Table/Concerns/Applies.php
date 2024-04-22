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

    /**
     * Apply the parameters to the records once to ensure iteration is not repeated
     */
    private function applyColumn(Column $column, mixed $record)
    {
        $name = $column->getName();
        $field = $record instanceof Model ? $record[$name] : $record->{$name};
        return empty($field) ? $column->getFallback() : $column->transformUsing($field);
    }

    public function applyColumns(array &$records, Collection $cols)
    {
        foreach ($records as &$row) {
            foreach ($cols->toArray() as $col) {
                $name = $col->getName();
                $row[$name] = $this->applyColumn($col, $row);
            }
        }
    }

    public function applyActionDependency(array &$records, Collection $inlineActions)
    {
        foreach ($records as &$record) {
            $record['actions'] = $inlineActions->filter(function (BaseAction $action) use ($record) {
                return $action->evaluateConditional($record);
            });
        }
    }

    public function applyActionRouting(array &$records, Collection $inlineActions)
    {
        foreach ($records as &$record) {
            $record['actions'] = $inlineActions->map(function (BaseAction $action) use ($record) {
                return array_merge($action->jsonSerialize(), $action->resolveEndpoint($record) ?? []);
            });
        }
    }
}

