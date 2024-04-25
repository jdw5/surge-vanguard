<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Jdw5\Vanguard\Table\Actions\BaseAction;
use Jdw5\Vanguard\Table\Actions\InlineAction;
use Jdw5\Vanguard\Table\Columns\Column;

/**
 * Trait HasConfiguration
 * 
 * Store core configuration parameters for the table
 */
trait HasScopes
{
    /** Remove all scopes */
    protected bool $scopeless = false;
    /** Scope the record transformation */
    protected bool $scopeTransform = true;
    /** Scope the columns and reduce to only take those which are defined */
    protected bool $scopeColumns = true;
    /** Scope the actions to the records */
    protected bool $scopeActions = true;
    /** Scope the action endpoints to the records */
    protected bool $scopeEndpoints = true;

    public function hasScopes(): bool
    {
        return ! $this->scopeless;
    }

    public function doesNotHaveScopes(): bool
    {
        return $this->scopeless;
    }

    public function scopesAndTransformsColumns(): bool
    {
        return $this->scopeColumns && $this->scopeTransform;
    }

    public function scopesAndRoutesActions(): bool
    {
        return $this->scopeActions && $this->scopeEndpoints;
    }

    public function applyScopes(Collection &$records, Collection $cols, Collection $actions): void
    {
        if ($this->doesNotHaveScopes()) {
            return;
        }
       
        $records->map(function ($record) use ($cols, $actions) {
            // Apply the columns
            if ($this->scopesAndTransformsColumns()) {
                $record = $cols->reduce(function ($carry, Column $col) use ($record) {
                    $name = $col->getName();
                    $carry[$name] = $this->applyColumn($col, $record);
                    return $carry;
                }, []);

            } else if ($this->scopeColumns) {
                $record = $cols->reduce(function ($carry, Column $col) use ($record) {
                    $name = $col->getName();
                    $field = $record instanceof Model ? $record[$name] : $record->{$name};
                    $carry[$name] = $field;
                    return $carry;
                }, []);

            } else if ($this->scopeTransform) {
                $cols->map(function (Column $col) use ($record) {
                    $name = $col->getName();
                    $record[$name] = $this->applyColumn($col, $record);
                });
            }

            // Apply the actions
            if ($this->scopesAndRoutesActions()) {
                $record['actions'] = $actions->reduce(function (array $carry, InlineAction $action) use ($record) {
                    if ($action->evaluateConditional($record)) {
                        array_push($carry, $action);
                    }
                    return $carry;
                }, []);
            } else if ($this->scopeActions) {
                $record['actions'] = $actions->reduce(function (array $carry, InlineAction $action) use ($record) {
                    if ($action->evaluateConditional($record)) {
                        array_push($carry, $action);
                    }
                    return $carry;
                }, []);
            } else if ($this->scopeEndpoints) {
                $record['actions'] = $actions->map(function (BaseAction $action) use ($record) {
                    return array_merge($action->jsonSerialize(), $action->resolveEndpoint($record) ?? []);
                });
            }
            // dd($record['actions']);
            return $record;
        });


        // foreach ($records as &$record) {

        //     if ($this->scopeTransform && $this->scopeColumns) {
        //         $record = $cols->reduce(function ($carry, Column $col) use ($record) {
        //             $name = $col->getName();
        //             $carry[$name] = $this->applyColumn($col, $record);
        //             return $carry;
        //         }, []);     

        //     } else if ($this->scopeTransform) {
        //         // Only apply the transforms, leave the other columns untouched                
        //         foreach ($cols->toArray() as $col) {
        //             $name = $col->getName();
        //             if (isset($record[$name])) {
        //                 $record[$name] = $this->applyColumn($col, $record);
        //             }
        //         }
        //     } else if ($this->scopeColumns) {
        //         $record = $cols->reduce(function ($carry, Column $col) use ($record) {
        //             $name = $col->getName();
        //             $field = $record instanceof Model ? $record[$name] : $record->{$name};
        //             $carry[$name] = $field;
        //             return $carry;
        //         }, []);                
        //     }

        //     if ($this->scopeActions && $this->scopeEndpoints) {
        //         # Needs to filter AND map

        //         $record['actions'] = $inlineActions->filter(function (InlineAction $action) use ($record) {
        //             return $action->evaluateConditional($record);
        //         })->values();
        //     } else if ($this->scopeActions) {
        //         // $record['actions'] = $inlineActions->filter(function (InlineAction $action) use ($record) {
        //         //     return $action->evaluateConditional($record);
        //         // })->values();
        //     } else if ($this->scopeEndpoints) {
        //         // $record['actions'] = $inlineActions->map(function (BaseAction $action) use ($record) {
        //         //     return array_merge($action->jsonSerialize(), $action->resolveEndpoint($record) ?? []);
        //         // });
        //     }
        // }

         
            // dd($record['actions']);

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
        return $inlineActions->filter(function (InlineAction $action) use ($record) {
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

