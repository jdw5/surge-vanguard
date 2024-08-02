<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Chunking;

use Closure;

trait Chunks
{
    use HasChunkSize;
    use IsChunkingById;

    public function chunk(int|Closure $size = null, int|Closure $chunkById = null): static
    {
        $this->setChunkSize($size);
        $this->setChunkById($chunkById);

        return $this;
    }

    public function getChunkMethod(): string
    {
        return $this->isChunkingById() ? 'chunkById' : 'chunk';
    }
}
