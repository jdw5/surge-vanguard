<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Jdw5\Vanguard\Table\Exceptions\TableModelNotFoundException;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasModel
 * 
 * Apply a model class for the table
 * 
 * @property \Closure|null $getModelClassesUsing
 */
trait HasModel
{
    protected static ?\Closure $getModelClassesUsing = null;

    /**
     * Set the callback to use for getting the model class
     * 
     * @param \Closure $callback
     */
    public static function getModelClassesUsing(\Closure $callback): void
    {
        static::$getModelClassesUsing = $callback;
    }

    /**
     * Retrieve the model for the table
     * 
     * @return Model
     */
    public function getModel(): Model
    {
        $model = $this->getModelClass();

        if (!class_exists($model)) {
            throw TableModelNotFoundException::invalid(static::class, $model);
        }

        if (!is_a($model, class: Model::class, allow_string: true)) {
            throw TableModelNotFoundException::notModel(static::class, $model);
        }

        return new $model();
    }

    /**
     * Retrieve the model class for the table
     * 
     * @return string
     */
    public function getModelClass(): string
    {
        if (isset($this->model)) {
            return $this->model;
        }

        if (isset(self::$getModelClassesUsing)) {
            return self::$getModelClassesUsing->call($this, static::class);
        }

        // if (isset($this->table)) {
        //     return $this->table;
        // }

        return str(static::class)
            ->classBasename()
            ->beforeLast('Table')
            ->singular()
            ->prepend('\\App\\Models\\')
            ->toString();
    }

    /**
     * Retrieve the key name for the model
     * 
     * @return string
     */
    public function getKeyName(): string
    {
        return $this->getModel()->getKeyName();
    }

    /**
     * Define the query for the table
     * 
     * @return Illuminate\Database\Eloquent\Builder|Illuminate\Database\Query\Builder
     */
    protected function defineQuery()
    {
        return $this->getModel()->query();
    }
}
