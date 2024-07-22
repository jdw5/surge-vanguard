<?php

namespace Conquest\Table\Filters\Concerns;

use Conquest\Table\Filters\Enums\DateClause;

trait HasDateClause
{
    /** Default to be exact */
    protected ?DateClause $dateClause = null;

    /**
     * Set the clause to be used.
     *
     * @param string|DateClause $dateClause
     * @return static
     */
    public function dateClause(string|DateClause $dateClause): static
    {
        $this->setDateClause($dateClause);

        return $this;
    }

    /**
     * Check if the clause is not set.
     * 
     * @return bool
     */
    public function lacksDateClause(): bool
    {
        return is_null($this->dateClause);
    }

    /**
     * Check if the clause is set.
     * 
     * @return bool
     */
    public function hasDateClause(): bool
    {
        return !$this->lacksDateClause();
    }

    /**
     * Set the clause to be used quietly.
     *
     * @param string|DateClause|null $dateClause
     * @return void
     */
    protected function setDateClause(string|DateClause|null $dateClause): void
    {
        if (is_null($dateClause)) {
            return;
        }

        if ($dateClause instanceof DateClause) {
            $this->dateClause = $dateClause;
        } else {
            $this->dateClause = DateClause::tryFrom($dateClause);
        }
    }

    /**
     * Retrieve the clause property.
     * 
     * @return DateClause|null
     */
    public function getClause(): ?DateClause
    {
        return $this->dateClause;
    }

    /**
     * Set the clause to be 'exact'.
     * 
     * @return static
     */
    public function date(): static
    {
        return $this->dateClause(DateClause::DATE);
    }

    /**
     * Set the clause to be 'day'.
     * 
     * @return static
     */
    public function day(): static
    {
        return $this->dateClause(DateClause::DAY);
    }

    /**
     * Set the clause to be 'month'.
     * 
     * @return static
     */
    public function month(): static
    {
        return $this->dateClause(DateClause::MONTH);
    }

    /**
     * Set the clause to be 'year'.
     * 
     * @return static
     */
    public function year(): static
    {
        return $this->dateClause(DateClause::YEAR);
    }

    /**
     * Set the clause to be 'time'.
     * 
     * @return static
     */
    public function time(): static
    {
        return $this->dateClause(DateClause::TIME);
    }
}
