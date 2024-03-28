<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

trait HasMeta
{
        /**
     * Generate the metadata for an unpaginated collection.
     * 
     * @param Collection $collection
     * @return array
     */
    private function generateUnpaginatedMeta(Collection $collection): array
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
    private function generateCursorPaginatorMeta(CursorPaginator $paginator): array
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
    private function generatePaginatorMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
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