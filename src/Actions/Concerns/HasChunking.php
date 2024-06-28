<?php

namespace Conquest\Table\Actions\Concerns;

trait HasChunking
{
    protected int $chunkSize = 1000;
    protected bool $chunkById = true;

    protected function setChunkSize(int $size): void
    {
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