<?php

namespace Conquest\Table\Actions\Concerns;

trait HasChunking
{
    protected int $chunkSize = 500;
    protected bool $chunkById = true;

    public function chunk(int $size = 500, bool $byId = true): static
    {
        $this->setChunkSize($size);
        $this->setChunkById($byId);
        return $this;
    }

    protected function setChunkSize(int|null $size): void
    {
        if (is_null($size)) return;
        $this->chunkSize = $size;
    }

    protected function setChunkById(bool|null $byId): void
    {
        if (is_null($byId)) return;
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

    public function getChunkMethod(): string
    {
        return $this->usesIdForChunking() ? 'chunkById' : 'chunk';
    }
}
