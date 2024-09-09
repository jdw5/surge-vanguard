<?php

declare(strict_types=1);

namespace Conquest\Table\Filters\Concerns;

use Conquest\Table\Filters\Enums\Operator;

trait HasOperator
{
    protected ?Operator $operator = null;

    /**
     * Set the operator to be used, chainable.
     */
    public function operator(string|Operator $operator): static
    {
        $this->setOperator($operator);

        return $this;
    }

    /**
     * Set the operator to be used quietly.
     *
     * @throws ValueError
     */
    public function setOperator(string|Operator|null $operator): void
    {
        if (is_null($operator)) {
            return;
        }
        $this->operator = $operator instanceof Operator ? $operator : Operator::from($operator);
    }

    /**
     * Get the operator to be used.
     */
    public function getOperator(): ?Operator
    {
        return $this->operator;
    }

    public function lacksOperator(): bool
    {
        return is_null($this->operator);
    }

    public function hasOperator(): bool
    {
        return ! $this->lacksOperator();
    }

    /**
     * Set the operator to be '>'.
     */
    public function gt(): static
    {
        $this->setOperator(Operator::GreaterThan);

        return $this;
    }

    /**
     * Set the operator to be '>='.
     */
    public function gte(): static
    {
        $this->setOperator(Operator::GreaterThanOrEqual);

        return $this;
    }

    /**
     * Set the operator to be '<'.
     */
    public function lt(): static
    {
        $this->setOperator(Operator::LessThan);

        return $this;
    }

    /**
     * Set the operator to be '<='.
     */
    public function lte(): static
    {
        $this->setOperator(Operator::LessThanOrEqual);

        return $this;
    }

    /**
     * Set the operator to be '='.
     */
    public function eq(): static
    {
        $this->setOperator(Operator::Equal);

        return $this;
    }

    /**
     * Set the operator to be '!='.
     */
    public function neq(): static
    {
        $this->setOperator(Operator::NotEqual);

        return $this;
    }

    /**
     * Alias for eq().
     */
    public function equals(): static
    {
        return $this->eq();
    }

    /**
     * Alias for eq().
     */
    public function equal(): static
    {
        return $this->eq();
    }

    /**
     * Alias for neq().
     */
    public function notEqual(): static
    {
        return $this->neq();
    }

    /**
     * Alias for gt().
     */
    public function greaterThan(): static
    {
        return $this->gt();
    }

    /**
     * Alias for gte().
     */
    public function greaterThanOrEqual(): static
    {
        return $this->gte();
    }

    /**
     * Alias for lt().
     */
    public function lessThan(): static
    {
        return $this->lt();
    }

    /**
     * Alias for lte().
     */
    public function lessThanOrEqual(): static
    {
        return $this->lte();
    }

    /**
     * Set the operator to be 'like'.
     */
    public function fuzzy(): static
    {
        $this->setOperator(Operator::Like);

        return $this;
    }

    /**
     * Alias for gt().
     */
    public function greater(): static
    {
        return $this->gt();
    }

    /**
     * Alias for lt().
     */
    public function lesser(): static
    {
        return $this->lt();
    }
}
