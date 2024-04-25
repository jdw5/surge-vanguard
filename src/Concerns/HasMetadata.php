<?php

namespace Jdw5\Vanguard\Concerns;

/**
 * Set metadata properties on a class
 */
trait HasMetadata
{
    /** Metadata are non-uniform properties for the class */
    protected array|\Closure $metadata = [];

    /** 
     * Set the metadata, chainable.
     * 
     * @param array|\Closure $metadata
     * @return static
     */
    public function metadata(array|\Closure $metadata): static
    {
        $this->setMetadata($metadata);
        return $this;
    }

    /**
     * Set the metadata quietly.
     * 
     * @param array|\Closure $metadata
     * @return void
     */
    protected function setMetadata(array|\Closure $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * Get the metadata.
     * 
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->evaluate($this->metadata);
    }
}
