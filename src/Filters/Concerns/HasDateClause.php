<?php

namespace Conquest\Table\Filters\Concerns;

use Conquest\Table\Filters\Enums\DateClause;

trait HasDateClause
{
    /** Default to be exact */
    protected ?DateClause $dateClause = null;

    /**
     * Set the clause to be used.
     */
    public function dateClause(string|DateClause $dateClause): static
    {
        $this->setDateClause($dateClause);

        return $this;
    }

    /**
     * Check if the clause is not set.
     */
    public function lacksDateClause(): bool
    {
        return is_null($this->dateClause);
    }

    /**
     * Check if the clause is set.
     */
    public function hasDateClause(): bool
    {
        return ! $this->lacksDateClause();
    }

    /**
     * Set the clause to be used quietly.
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
     */
    public function getClause(): ?DateClause
    {
        return $this->dateClause;
    }

    /**
     * Set the clause to be 'exact'.
     */
    public function date(): static
    {
        return $this->dateClause(DateClause::DATE);
    }

    /**
     * Set the clause to be 'day'.
     */
    public function day(): static
    {
        return $this->dateClause(DateClause::DAY);
    }

    /**
     * Set the clause to be 'month'.
     */
    public function month(): static
    {
        return $this->dateClause(DateClause::MONTH);
    }

    /**
     * Set the clause to be 'year'.
     */
    public function year(): static
    {
        return $this->dateClause(DateClause::YEAR);
    }

    /**
     * Set the clause to be 'time'.
     */
    public function time(): static
    {
        return $this->dateClause(DateClause::TIME);
    }
}
