<?php

declare(strict_types=1);

namespace Conquest\Table\Actions\Concerns\Chunk;

use Closure;

trait Chunks
{
    use HasChunkById;
    use HasChunkSize;

    public function chunk(int|Closure|null $size = null, bool|Closure|null $chunkById = null): static
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
