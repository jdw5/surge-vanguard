<?php

namespace Conquest\Table\Refining\Filters\Concerns;

trait HasDateOperations
{
    protected string|\Closure $dateOperator = 'whereDate';

    public function dateOperator(string|\Closure $operator): static
    {
        $this->dateOperator = $operator;

        return $this;
    }

    public function day()
    {
        $this->dateOperator = 'whereDay';

        return $this;
    }

    public function month()
    {
        $this->dateOperator = 'whereMonth';

        return $this;
    }

    public function year()
    {
        $this->dateOperator = 'whereYear';

        return $this;
    }

    public function date()
    {
        $this->dateOperator = 'whereDate';

        return $this;
    }

    public function time()
    {
        $this->dateOperator = 'whereTime';

        return $this;
    }

    public function getDateOperator(): string
    {
        return $this->evaluate($this->dateOperator);
    }
}
