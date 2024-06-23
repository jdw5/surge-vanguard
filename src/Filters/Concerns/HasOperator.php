<?php

namespace Jdw5\Vanguard\Refining\Filters\Concerns;

trait HasOperator
{
    /** Default the operator to an exact equal match */
    protected string|\Closure $operator = '=';

    /**
     * Set the operator to be used, chainable.
     * 
     * @param string|\Closure $operator
     * @return static
     */
    public function operator(string|\Closure $operator): static
    {
        $this->setOperator($operator);
        return $this;
    }

    /**
     * Set the operator to be used quietly.
     * 
     * @param string|\Closure $operator
     * @return void
     */
    public function setOperator(string|\Closure $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * Set the operator to be '>'.
     * 
     * @return static
     */
    public function gt(): static
    {
        $this->setOperator('>');
        return $this;
    }

    /**
     * Set the operator to be '>='.
     * 
     * @return static
     */
    public function gte(): static
    {
        $this->setOperator('>=');
        return $this;
    }

    /**
     * Set the operator to be '<'.
     * 
     * @return static
     */
    public function lt(): static
    {
        $this->setOperator('<');
        return $this;
    }

    /**
     * Set the operator to be '<='.
     * 
     * @return static
     */
    public function lte(): static
    {
        $this->setOperator('<=');
        return $this;
    }

    /**
     * Set the operator to be '='.
     * 
     * @return static
     */
    public function eq(): static
    {
        $this->setOperator('=');
        return $this;
    }

    /**
     * Set the operator to be '!='.
     * 
     * @return static
     */
    public function neq(): static
    {
        $this->setOperator('!=');
        return $this;
    }

    /**
     * Get the operator to be used.
     * 
     * @return string
     */
    public function getOperator(): string
    {
        return $this->evaluate($this->operator);
    }

    /**
     * Negate the operator.
     * 
     * @return static
     */
    public function not(): static
    {
        $this->setOperator(match ($this->operator) {
            '!=' => '=',
            '>' => '<=',
            '>=' => '<',
            '<' => '>=',
            '<=' => '>',
            default => '!='
        });
        
        return $this;
    }
}