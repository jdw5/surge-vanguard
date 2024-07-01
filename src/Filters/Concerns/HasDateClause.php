<?php

namespace Conquest\Table\Filters\Concerns;

use Conquest\Table\Filters\Enums\DateClause;
use Conquest\Table\Filters\Exceptions\InvalidClause;
use Exception;

trait HasDateClause
{
    /** Default to be exact */
    protected DateClause $dateClause = DateClause::DATE;

    /**
     * Set the clause to be used.
     *
     * @throws InvalidClause
     */
    public function dateClause(string|DateClause $dateClause): static
    {
        $this->setDateClause($dateClause);

        return $this;
    }

    /**
     * Set the clause to be used quietly.
     *
     * @throws InvalidClause
     */
    protected function setDateClause(string|DateClause|null $dateClause): void
    {
        if (is_null($dateClause)) {
            return;
        }

        if ($dateClause instanceof DateClause) {
            $this->dateClause = $dateClause;
        } else {
            try {
                $this->dateClause = DateClause::from($dateClause);
            } catch (Exception $e) {
                throw new InvalidClause($dateClause);
            }
        }
    }

    /**
     * Retrieve the clause property.
     */
    public function getClause(): DateClause
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
