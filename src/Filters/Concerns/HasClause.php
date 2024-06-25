<?php

namespace Jdw5\Vanguard\Filters\Concerns;

use Exception;
use Jdw5\Vanguard\Filters\Enums\Clause;
use Jdw5\Vanguard\Filters\Exceptions\InvalidClause;

trait HasClause
{
    /** Default to be exact */
    protected Clause $clause = Clause::IS;
    
    /**
     * Set the mode to be used.
     * 
     * @param string|Clause $clause
     * @return static
     * @throws InvalidMode 
     */
    public function clause(string|Clause $clause): static
    {
        $this->setMode($clause);
        return $this;
    }

    /**
     * Set the mode to be used quietly.
     * 
     * @param string|Clause $clause
     * @return void
     * @throws InvalidClause
     */
    protected function setMode(string|Clause $clause): void
    {
        if ($clause instanceof Clause) {
            $this->clause = $clause;
        }
        else {
            try {
                $this->clause = Clause::from($clause);
            } catch (Exception $e) {
                throw new InvalidClause($clause);
            }
        }
    }

    /**
     * Retrieve the mode property.
     * 
     * @return Clause
     */
    public function getMode(): Clause
    {
        return $this->clause;
    }

    /**
     * Set the mode to be 'exact'.
     * 
     * @return static
     */
    public function is(): static
    {
        return $this->clause(Clause::IS);
    }

    /**
     * Set the mode to be 'loose'.
     * 
     * @return static
     */
    public function isNot(): static
    {
        return $this->clause(Clause::IS_NOT);
    }

    /**
     * Set the mode to be 'begins with'.
     * 
     * @return static
     */
    public function startsWith(): static
    {
        return $this->clause(Clause::STARTS_WITH);
    }

    public function beginsWith(): static
    {
        return $this->startsWith();
    }

    /**
     * Set the mode to be 'ends with'.
     * 
     * @return static
     */
    public function endsWith(): static
    {
        return $this->clause(Clause::ENDS_WITH);
    }

    public function contains(): static
    {
        return $this->clause(Clause::CONTAINS);
    }

    public function doesNotContain(): static
    {
        return $this->clause(Clause::DOES_NOT_CONTAIN);
    }

    public function doesntContain(): static
    {
        return $this->doesNotContain();
    }

    public function all(): static
    {
        return $this->clause(Clause::ALL);
    }

    public function any(): static
    {
        return $this->clause(Clause::ANY);
    }

    public function jsonContains(): static
    {
        return $this->clause(Clause::JSON);
    }

    public function json(): static
    {
        return $this->jsonContains();
    }

    public function jsonDoesNotContain(): static
    {
        return $this->clause(Clause::NOT_JSON);
    }

    public function notJson(): static
    {
        return $this->jsonDoesNotContain();
    }

    public function jsonLength(): static
    {
        return $this->clause(Clause::JSON_LENGTH);
    }

    public function multiple(): static
    {
        return $this->clause(Clause::CONTAINS);
    }

    public function multipleNot(): static
    {
        return $this->clause(Clause::DOES_NOT_CONTAIN);
    }
}