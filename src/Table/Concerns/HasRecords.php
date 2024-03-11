<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Jdw5\Vanguard\Concerns\HasRefinements;
use Jdw5\Vanguard\Table\Columns\Column;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

trait HasRecords
{
    use HasPagination;
    use HasRefinements;
    use HasColumns;

    protected mixed $cachedMeta = null;
    protected mixed $cachedData = null;
    private $query = null;

    public function query(Builder $query = null): ?Builder
    {
        if ($query) {
            $this->query = $query;
        }
        return $this->query;
    }

    public function hasQuery(): bool
    {
        return ! empty($this->query);
    }

    public function pipelineWithData(): mixed 
    {
        $this->pipeline();
        return $this->cachedData;
    }

    public function pipelineWithMeta(): mixed
    {
        $this->pipeline();
        return $this->cachedMeta;
    }

    protected function pipeline(): void
    {
        if (! $this->hasQuery()) { 
            $this->query = $this->defineQuery();
        }
        
        $this->query($this->query
            ->withRefinements($this->getRefinements())
            ->withRefinements($this->getSortableColumns()->map
                ->getSort()
                ->filter()
                ->toArray()
            )
        );


        match ($this->paginateType()) {
            'paginate' => $this->handlePaginate(),
            'cursor' => $this->handleCursorPaginate(),
            default => $this->handleUnpaginated(),
        };

        $this->applyColumns();
    }

    protected function applyColumns(): void
    {
        $this->cachedData = collect($this->cachedData)->map(function ($row) {
            return $this->getTableColumns()->reduce(function ($carry, Column $column) use ($row) {
                $name = $column->getName();
                $carry[$name] = $column->transformUsing($row[$name] ?? $column->getFallback());
                return $carry;
            }, []);
        });      
    }

    private function handlePaginate(): void
    {
        $paginatedData = $this->query->paginate(...$this->unpackPaginateToArray())->withQueryString();
        $this->cachedData = $paginatedData->items();
        $this->cachedMeta = $this->generatePaginatorMeta($paginatedData);
    }

    private function handleCursorPaginate(): void
    {
        $cursorPaginatedData = $this->query->cursorPaginate(...$this->unpackPaginateToArray())->withQueryString();
        $this->cachedData = $cursorPaginatedData->items();
        $this->cachedMeta = $this->generateCursorPaginatorMeta($cursorPaginatedData);
    }

    private function handleUnpaginated(): void
    {
        $this->cachedData = $this->query->get()->withQueryString();
        $this->cachedMeta = $this->generateUnpaginatedMeta($this->cachedData);
    }

    private function generateUnpaginatedMeta(Collection $collection): array
    {
        return [
            'empty' => $collection->isEmpty(),
            'show' => $collection->isNotEmpty(),
        ];
    }

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


    public function getRecords(): mixed
    {
        return $cachedData ??= $this->pipelineWithData();
    }

    public function getMeta(): array
    {
        return $cachedMeta ??= $this->pipelineWithMeta();
    }
}