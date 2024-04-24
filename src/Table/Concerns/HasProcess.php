<?php

namespace Jdw5\Vanguard\Table\Concerns;

trait HasProcess
{
    private bool $processed = false;

    abstract public function tablePipeline(): void;

    public function process(): void
    {
        if ($this->hasBeenProcessed()) {
            return;
        }

        $this->tablePipeline();

        $this->processed = true;
    }

    public function hasBeenProcessed(): bool
    {
        return $this->evaluate($this->processed);
    }

    public function setProcessed(bool $processed): void
    {
        $this->processed = $processed;
    }

    public function hasNotBeenProcessed(): bool
    {
        return ! $this->hasBeenProcessed();
    }


}