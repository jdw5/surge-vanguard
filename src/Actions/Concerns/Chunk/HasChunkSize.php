<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Chunk;

use Closure;

trait HasChunkSize
{
    protected int|Closure|null $chunkSize = null;

    public function chunkSize(int|Closure $chunkSize): static
    {
        $this->setChunkSize($chunkSize);

        return $this;
    }

    public function setChunkSize(int|Closure|null $chunkSize): void
    {
        if (is_null($chunkSize)) {
            return;
        }
        $this->chunkSize = $chunkSize;
    }

    public function getChunkSize(): int
    {
        return $this->evaluate($this->chunkSize) ?? config('table.chunk.size');
    }

    public function hasChunkSize(): bool
    {
        return ! is_null($this->chunkSize);
    }

    public function lacksChunkSize(): bool
    {
        return ! $this->hasChunkSize();
    }
}
