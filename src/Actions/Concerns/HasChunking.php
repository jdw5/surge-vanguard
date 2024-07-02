<?php

namespace Conquest\Table\Actions\Concerns;

trait HasChunking
{
    /** Number of elements to be brought int */
    protected int $chunkSize = 1000;
    /** Whether the query should be by ID (recommended) */
    protected bool $chunkById = true;
    /** If one of the IDs cannot be found, whether or not to proceed with updating the rest */
    protected $failOnFirst = false;

    protected function setChunkSize(int|null $size): void
    {
        if (is_null($size)) return;
        $this->chunkSize = $size;
    }

    protected function setChunkById(bool $byId): void
    {
        $this->chunkById = $byId;
    }

    public function chunkSize(int $size): static
    {
        $this->setChunkSize($size);
        return $this;
    }

    public function chunkById(bool $byId = true): static
    {
        $this->setChunkById($byId);
        return $this;
    }

    public function getChunkSize(): int
    {
        return $this->chunkSize;
    }

    public function usesIdForChunking(): bool
    {
        return $this->chunkById;
    }
}