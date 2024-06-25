<?php

namespace Jdw5\Vanguard\Filters\Concerns;

use Exception;
use Jdw5\Vanguard\Filters\Enums\Operator;
use Jdw5\Vanguard\Filters\Exceptions\InvalidOperator;

trait HasOperator
{
    protected Operator $operator = Operator::EQUAL;

    /**
     * Set the operator to be used, chainable.
     * 
     * @param string|Operator $operator
     * @return static
     */
    public function operator(string|Operator $operator): static
    {
        $this->setOperator($operator);
        return $this;
    }

    /**
     * Set the operator to be used quietly.
     * 
     * @param string|Operator $operator
     * @return void
     */
    public function setOperator(string|Operator $operator): void
    {
        if ($operator instanceof Operator) {
            $this->operator = $operator;
        } 
        else {
            try {
                $this->operator = operator::from($operator);
            } catch (Exception $e) {
                throw new InvalidOperator($operator);
            }
        }
    }

    /**
     * Get the operator to be used.
     * 
     * @return Operator
     */
    public function getOperator(): Operator
    {
        return $this->evaluate($this->operator);
    }

    /**
     * Set the operator to be '>'.
     * 
     * @return static
     */
    public function gt(): static
    {
        $this->setOperator(Operator::GREATER_THAN);
        return $this;
    }

    /**
     * Set the operator to be '>='.
     * 
     * @return static
     */
    public function gte(): static
    {
        $this->setOperator(Operator::GREATER_THAN_OR_EQUAL);
        return $this;
    }

    /**
     * Set the operator to be '<'.
     * 
     * @return static
     */
    public function lt(): static
    {
        $this->setOperator(Operator::LESS_THAN);
        return $this;
    }

    /**
     * Set the operator to be '<='.
     * 
     * @return static
     */
    public function lte(): static
    {
        $this->setOperator(Operator::LESS_THAN_OR_EQUAL);
        return $this;
    }

    /**
     * Set the operator to be '='.
     * 
     * @return static
     */
    public function eq(): static
    {
        $this->setOperator(Operator::EQUAL);
        return $this;
    }

    /**
     * Set the operator to be '!='.
     * 
     * @return static
     */
    public function neq(): static
    {
        $this->setOperator(Operator::NOT_EQUAL);
        return $this;
    }

    public function like(): static
    {
        $this->setOperator(Operator::LIKE);
        return $this;
    }

    // Alias
    public function equals(): static
    {
        return $this->eq();
    }

    public function equal(): static
    {
        return $this->eq();
    }

    public function notEqual(): static
    {
        return $this->neq();
    }

    public function greaterThan(): static
    {
        return $this->gt();
    }

    public function greaterThanOrEqual(): static
    {
        return $this->gte();
    }

    public function lessThan(): static
    {
        return $this->lt();
    }

    public function lessThanOrEqual(): static
    {
        return $this->lte();
    }

    public function similar(): static
    {
        return $this->like();
    }

    public function fuzzy(): static
    {
        return $this->like();
    }

    public function greater(): static
    {
        return $this->gt();
    }

    public function less(): static
    {
        return $this->lt();
    }
}