<?php

namespace Jdw5\Vanguard\Refining\Filters\Concerns;

trait HasOperator
{
    protected string|\Closure $operator = '=';

    public function operator(string|\Closure $operator): static
    {
        $this->operator = $operator;
        return $this;
    }

    public function setOperator(string|\Closure $operator): static
    {
        $this->operator = $operator;
        return $this;
    }

    public function gt(): static
    {
        $this->operator = '>';
        return $this;
    }

    public function gte(): static
    {
        $this->operator = '>=';
        return $this;
    }

    public function lt(): static
    {
        $this->operator = '<';
        return $this;
    }

    public function lte(): static
    {
        $this->operator = '<=';
        return $this;
    }

    public function eq(): static
    {
        $this->operator = '=';
        return $this;
    }

    public function neq(): static
    {
        $this->operator = '!=';
        return $this;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function not(): static
    {
        $this->operator = match ($this->operator) {
            '!=' => '=',
            '>' => '<=',
            '>=' => '<',
            '<' => '>=',
            '<=' => '>',
            default => '!='
        };
        
        return $this;
    }
}