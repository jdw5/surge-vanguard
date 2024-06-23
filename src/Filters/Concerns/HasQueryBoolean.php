<?php

namespace Jdw5\Vanguard\Refining\Filters\Concerns;

trait HasQueryBoolean
{
    /** The query boolean to be used for SQL clauses. Should resolve to 'and' or 'or' */
    protected string|\Closure $queryBoolean = 'and';

    /**
     * Set the query boolean to be used.
     * 
     * @param string $boolean
     * @return static
     */
    public function queryBoolean(string|\Closure $boolean): static
    {
        $this->setQueryBoolean($boolean);
        return $this;
    }

    protected function setQueryBoolean(string|\Closure $boolean): void
    {
        $this->queryBoolean = $boolean;
    }

    /**
     * Set the query boolean to be 'or'.
     * 
     * @return static
     */
    public function or(): static
    {
        return $this->queryBoolean('or');
    }

    /**
     * Set the query boolean to be 'and'.
     * 
     * @return static
     */
    public function and(): static
    {
        return $this->queryBoolean('and');
    }

    public function getQueryBoolean(): string
    {
        return $this->evaluate($this->queryBoolean);
    }
}