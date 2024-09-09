<?php

declare(strict_types=1);

namespace Conquest\Table\Filters\Concerns;

use Conquest\Table\Filters\Enums\Operator;
use Illuminate\Support\Collection;

trait HasOperators
{
    protected ?array $operators = null;

    /**
     * Set the operators to be used, chainable.
     *
     * @param  array<Operator>  $operators
     */
    public function operators(...$operators): static
    {
        $this->setOperators(...$operators);

        return $this;
    }

    /**
     * Set the operators to be used quietly.
     *
     * @param  array<Operator>  $operators
     */
    public function setOperators(...$operators): void
    {
        if (empty($operators)) {
            return;
        }
        // Ensure that the operators are all instances of Operator.
        $this->operators = $operators;
    }

    /**
     * Get the operators to be used.
     *
     * @return array<Operator>
     */
    public function getOperators(): array
    {
        return $this->operators ?? [];
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
        return ! $this->lacksOperators();
    }

    public function lacksOperators(): bool
    {
        return is_null($this->operators);
    }
}
