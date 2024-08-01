<?php

namespace Conquest\Table\Filters\Concerns;

use Conquest\Table\Filters\Enums\Clause;

trait HasClause
{
    protected ?Clause $clause = null;

    public function clause(string|Clause $clause): static
    {
        $this->setClause($clause);

        return $this;
    }

    protected function setClause(string|Clause|null $clause): void
    {
        if (is_null($clause)) {
            return;
        }

        if ($clause instanceof Clause) {
            $this->clause = $clause;
        } else {
            $this->clause = Clause::tryFrom($clause);
        }
    }

    public function getClause(): ?Clause
    {
        return $this->clause;
    }

    public function is(): static
    {
        return $this->clause(Clause::IS);
    }

    public function isNot(): static
    {
        return $this->clause(Clause::IS_NOT);
    }

    public function startsWith(): static
    {
        return $this->clause(Clause::STARTS_WITH);
    }

    public function beginsWith(): static
    {
        return $this->startsWith();
    }

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

    public function all(): static
    {
        return $this->clause(Clause::ALL);
    }

    public function any(): static
    {
        return $this->clause(Clause::ANY);
    }

    public function json(): static
    {
        return $this->jsonContains();
    }

    public function notJson(): static
    {
        return $this->jsonDoesNotContain();
    }

    public function jsonLength(): static
    {
        return $this->clause(Clause::JSON_LENGTH);
    }

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

    public function like(): static
    {
        return $this->clause(Clause::LIKE);
    }
}
