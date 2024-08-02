<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Chunking;

use Closure;

trait Chunks
{
    use HasChunkSize;
    use HasChunkById;

    public function chunk(int|Closure $size = null, bool|Closure $chunkById = null): static
    {
        $this->setChunkSize($size);
        $this->setChunkById($chunkById);

        return $this;
    }

    public function getChunkMethod(): string
    {
        return $this->getChunkById() ? 'chunkById' : 'chunk';
    }
}
