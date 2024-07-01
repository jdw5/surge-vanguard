<?php

namespace Conquest\Table\Filters\Concerns;

use Conquest\Table\Filters\Enums\Clause;
use Conquest\Table\Filters\Exceptions\InvalidClause;
use Exception;

trait HasClause
{
    /** Default to be exact */
    protected Clause $clause = Clause::IS;

    /**
     * Set the clause to be used.
     *
     * @throws InvalidClause
     */
    public function clause(string|Clause $clause): static
    {
        $this->setClause($clause);

        return $this;
    }

    /**
     * Set the clause to be used quietly.
     *
     * @throws InvalidClause
     */
    protected function setClause(string|Clause|null $clause): void
    {
        if (is_null($clause)) {
            return;
        }

        if ($clause instanceof Clause) {
            $this->clause = $clause;
        } else {
            try {
                $this->clause = Clause::from($clause);
            } catch (Exception $e) {
                throw new InvalidClause($clause);
            }
        }
    }

    /**
     * Retrieve the clause property.
     */
    public function getClause(): Clause
    {
        return $this->clause;
    }

    /**
     * Set the clause to be 'exact'.
     */
    public function is(): static
    {
        return $this->clause(Clause::IS);
    }

    /**
     * Set the clause to be 'loose'.
     */
    public function isNot(): static
    {
        return $this->clause(Clause::IS_NOT);
    }

    /**
     * Set the clause to be 'begins with'.
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
     * Set the clause to be 'ends with'.
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

    // public function multiple(): static
    // {
    //     return $this->clause(Clause::CONTAINS);
    // }

    // public function multipleNot(): static
    // {
    //     return $this->clause(Clause::DOES_NOT_CONTAIN);
    // }

    public function fullText(): static
    {
        return $this->clause(Clause::FULL_TEXT);
    }

    public function search(): static
    {
        return $this->clause(Clause::SEARCH);
    }

    public function jsonKey(): static
    {
        return $this->clause(Clause::JSON_KEY);
    }

    public function notJsonKey(): static
    {
        return $this->clause(Clause::JSON_NOT_KEY);
    }

    public function jsonOverlap(): static
    {
        return $this->clause(Clause::JSON_OVERLAPS);
    }

    public function jsonOverlaps(): static
    {
        return $this->jsonOverlap();
    }

    public function jsonDoesntOverlap(): static
    {
        return $this->clause(Clause::JSON_DOESNT_OVERLAP);
    }

    public function jsonNotOverlap(): static
    {
        return $this->jsonDoesntOverlap();
    }
}
