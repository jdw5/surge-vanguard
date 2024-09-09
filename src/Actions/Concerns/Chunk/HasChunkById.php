<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Chunk;

use Closure;

trait HasChunkById
{
    protected bool|Closure|null $chunkById = null;

    public function chunkById(bool|Closure $chunkById = true): static
    {
        $this->setChunkById($chunkById);

        return $this;
    }

    public function setChunkById(bool|Closure|null $chunkById): void
    {
        if (is_null($chunkById)) {
            return;
        }
        $this->chunkById = $chunkById;
    }

    public function getChunkById(): bool
    {
        return $this->evaluate($this->chunkById) ?? config('table.chunk.by_id');
    }

    public function hasChunkById(): bool
    {
        return ! is_null($this->chunkById);
    }

    public function lacksChunkById(): bool
    {
        return ! $this->hasChunkById();
    }
}
