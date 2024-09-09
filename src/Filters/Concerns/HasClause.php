<?php

declare(strict_types=1);

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

    public function setClause(string|Clause|null $clause): void
    {
        if (is_null($clause)) {
            return;
        }

        $this->clause = $clause instanceof Clause ? $clause : Clause::tryFrom($clause);
    }

    public function lacksClause(): bool
    {
        return is_null($this->clause);
    }

    public function hasClause(): bool
    {
        return ! $this->lacksClause();
    }

    public function getClause(): ?Clause
    {
        return $this->clause;
    }

    public function is(): static
    {
        return $this->clause(Clause::Is);
    }

    public function isNot(): static
    {
        return $this->clause(Clause::IsNot);
    }

    public function startsWith(): static
    {
        return $this->clause(Clause::StartsWith);
    }

    public function beginsWith(): static
    {
        return $this->startsWith();
    }

    public function endsWith(): static
    {
        return $this->clause(Clause::EndsWith);
    }

    public function contains(): static
    {
        return $this->clause(Clause::Contains);
    }

    public function doesNotContain(): static
    {
        return $this->clause(Clause::DoesNotContain);
    }

    public function all(): static
    {
        return $this->clause(Clause::All);
    }

    public function any(): static
    {
        return $this->clause(Clause::Any);
    }

    public function json(): static
    {
        return $this->clause(Clause::Json);
    }

    public function notJson(): static
    {
        return $this->clause(Clause::NotJson);
    }

    public function jsonLength(): static
    {
        return $this->clause(Clause::JsonLength);
    }

    public function fullText(): static
    {
        return $this->clause(Clause::FullText);
    }

    public function search(): static
    {
        return $this->clause(Clause::Search);
    }

    public function jsonKey(): static
    {
        return $this->clause(Clause::JsonKey);
    }

    public function notJsonKey(): static
    {
        return $this->clause(Clause::JsonNotKey);
    }

    public function jsonOverlap(): static
    {
        return $this->clause(Clause::JsonOverlaps);
    }

    public function jsonOverlaps(): static
    {
        return $this->jsonOverlap();
    }

    public function jsonDoesNotOverlap(): static
    {
        return $this->clause(Clause::JsonDoesNotOverlap);
    }

    public function like(): static
    {
        return $this->clause(Clause::Like);
    }
}
