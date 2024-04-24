<?php

namespace Jdw5\Vanguard\Concerns;

trait HasMetadata
{
    protected array|\Closure $metadata = [];

    public function metadata(array|\Closure $metadata): static
    {
        $this->setMetadata($metadata);
        return $this;
    }

    protected function setMetadata(array|\Closure $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function getMetadata(): array
    {
        return $this->evaluate($this->metadata);
    }
}
