<?php

namespace Conquest\Table\Filters\Concerns;

use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Support\Collection;

trait HasOperators
{
    protected array $operators = [];

    /**
     * Set the operators to be used, chainable.
     *
     * @param  array<Operator>  $operators
     */
    public function operators(array $operators): static
    {
        $this->setOperators($operators);

        return $this;
    }

    /**
     * Set the operators to be used quietly.
     *
     * @param  array<Operator>|null  $operators
     */
    public function setOperators(?array $operators): void
    {
        if (is_null($operators)) {
            return;
        }
        $this->operators = $operators;
    }

    /**
     * Get the operators to be used.
     *
     * @return array<Operator>
     */
    public function getOperators(): array
    {
        return $this->operators;
    }

    /**
     * Get the operators.
     */
    public function getOperatorOptions(?string $active = null): Collection
    {
        return collect($this->getOperators())->map(fn (Operator $operator) => [
            'value' => $operator->value,
            'label' => $operator->label(),
            'active' => $operator->value === $active,
        ]);
    }

    public function hasOperators(): bool
    {
        return count($this->operators) > 0;
    }

    public function lacksOperators(): bool
    {
        return ! $this->hasOperators();
    }
}
