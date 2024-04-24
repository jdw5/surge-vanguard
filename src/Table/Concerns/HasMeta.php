<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

trait HasMeta
{
    private mixed $meta = null;

    abstract public function getMeta(): array;
    abstract function getPagination(): array;
    abstract function getPaginateType(): ?string;

    public function retrieveRecords(Builder|QueryBuilder $builder): array
    {
        switch ($this->getPaginateType())
        {
            case 'cursor':
                $data = $this->query->cursorPaginate(...\array_values($this->getPagination()))->withQueryString();
                return [$data->items(), $this->generateCursorPaginatorMeta($data)];
            case 'get':
                $data = $builder->get();
                return [$data, $this->generateUnpaginatedMeta($data)];
            default:
                $data = $this->query->paginate(...$this->getPagination())->withQueryString();
                return [$data->items(), $this->generatePaginatorMeta($data)];
        }
    }

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