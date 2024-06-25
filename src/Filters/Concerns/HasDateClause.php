<?php

namespace Jdw5\Vanguard\Filters\Concerns;

use Exception;
use Jdw5\Vanguard\Filters\Enums\DateClause;
use Jdw5\Vanguard\Filters\Exceptions\InvalidClause;

trait HasDateClause
{
    /** Default to be exact */
    protected DateClause $dateClause = DateClause::DATE;
    
    /**
     * Set the clause to be used.
     * 
     * @param string|DateClause $dateClause
     * @return static
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
     * @param string|DateClause $dateClause
     * @return void
     * @throws InvalidClause
     */
    protected function setDateClause(string|DateClause|null $dateClause): void
    {
        if (is_null($dateClause)) return;
        
        if ($dateClause instanceof DateClause) {
            $this->dateClause = $dateClause;
        }

        else {
            try {
                $this->dateClause = DateClause::from($dateClause);
            } catch (Exception $e) {
                throw new InvalidClause($dateClause);
            }
        }
    }

    /**
     * Retrieve the clause property.
     * 
     * @return DateClause
     */
    public function getClause(): DateClause
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