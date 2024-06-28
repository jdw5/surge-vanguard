<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

trait HasMeta
{
    protected array $meta = [];

    protected function setMeta(array|null $meta): void
    {
        if (empty($meta)) return;
        $this->meta = $meta;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function hasMeta(): bool
    {
        return !empty($this->meta);
    }

    /**
     * Generate the metadata for an unpaginated collection.
     * 
     * @param Collection $collection
     * @return array
     */
    public function getCollectionMeta(Collection $collection): array
    {
        return [
            'empty' => $collection->isEmpty(),
            'show' => $collection->isNotEmpty(),
        ];
    }

    /**
     * Generate the metadata for a cursor paginated collection.
     * 
     * @param CursorPaginator $paginator
     * @return array
     */
    public function getCursorMeta(CursorPaginator $paginator): array
    {
        return [
            'per_page' => $paginator->perPage(),
            'next_cursor' => $paginator->nextCursor()?->encode(),
            'prev_cusor' => $paginator->previousCursor()?->encode(),
            'next_url' => $paginator->nextPageUrl(),
            'prev_url' => $paginator->previousPageUrl(),

            'show' => $paginator->hasPages(),
            'empty' => $paginator->isEmpty(),
        ];
    }

    /**
     * Generate the metadata for a length aware paginated collection.
     * 
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    public function getPaginateMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem() ?? 0,
            'to' => $paginator->lastItem() ?? 0,
            'total' => $paginator->total(),
            'links' => $paginator->onEachSide(1)->linkCollection()->splice(1, -1)->values()->toArray(),
            'first_url' => $paginator->url(1),
            'last_url' => $paginator->url($paginator->lastPage()),
            'next_url' => $paginator->nextPageUrl(),
            'prev_url' => $paginator->previousPageUrl(),
            'show' => $paginator->hasPages(),
            'empty' => $paginator->isEmpty(),
        ];
    }
}