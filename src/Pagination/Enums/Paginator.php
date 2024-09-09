<?php

declare(strict_types=1);

namespace Conquest\Table\Pagination\Enums;

use Conquest\Table\Table;

enum Paginator: string
{
    case None = 'none';
    case Page = 'page';
    case Cursor = 'cursor';

    /**
     * Retrieve the records and metadata based on the selected paginator.
     *
     * @return array{\Illuminate\Support\Collection, array}
     */
    public function paginate(Table $table): array
    {
        $builder = $table->getResource();

        return match ($this) {
            self::Cursor => [
                $data = $builder->cursorPaginate(
                    perPage: $table->usePerPage(),
                    cursorName: $table->getPageName(),
                )->withQueryString(),
                self::getMeta($data),
            ],
            self::None => [
                $data = $builder->get(),
                self::getMeta($data),
            ],
            default => [
                $data = $builder->paginate(
                    perPage: $table->usePerPage(),
                    pageName: $table->getPageName(),
                )->withQueryString(),
                self::getMeta($data),
            ],
        };
    }

    /**
     * Get metadata based on the current pagination type.
     *
     * @param  \Illuminate\Support\Collection  $data  When self::None
     * @param  \Illuminate\Pagination\CursorPaginator  $data  When self::Cursor
     * @param  \Illuminate\Pagination\LengthAwarePaginator  $data  When self::Page
     */
    public function getMeta($data): array
    {
        return match ($this) {
            self::None => [
                'empty' => $data->isEmpty(),
                'show' => $data->isNotEmpty(),
            ],
            self::Cursor => [
                'per_page' => $data->perPage(),
                'next_cursor' => $data->nextCursor()?->encode(),
                'prev_cusor' => $data->previousCursor()?->encode(),
                'next_url' => $data->nextPageUrl(),
                'prev_url' => $data->previousPageUrl(),
                'show' => $data->hasPages(),
                'empty' => $data->isEmpty(),
            ],
            default => [
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem() ?? 0,
                'to' => $data->lastItem() ?? 0,
                'total' => $data->total(),
                'links' => $data->onEachSide(1)->linkCollection()->splice(1, -1)->values()->toArray(),
                'first_url' => $data->url(1),
                'last_url' => $data->url($data->lastPage()),
                'next_url' => $data->nextPageUrl(),
                'prev_url' => $data->previousPageUrl(),
                'show' => $data->hasPages(),
                'empty' => $data->isEmpty(),
            ],
        };
    }
}
