<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Chunking;

use Closure;

trait IsChunkingById
{
    protected bool|Closure $chunkById = false;

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

    public function isChunkingById(): bool
    {
        return $this->evaluate($this->chunkById);
    }

    public function isNotChunkById(): bool
    {
        return ! $this->isChunkingById();
    }
}
