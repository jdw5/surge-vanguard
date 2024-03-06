<?php

namespace Jdw5\Vanguard\Table\Columns\Concerns;

trait HasVisibility
{
    protected bool $show = true;

    public function hide(): static
    {
        $this->show = false;

        return $this;
    }

    public function show(): static
    {
        $this->show = true;

        return $this;
    }

    public function getVisibility(): bool
    {
        return $this->show;
    }
}
