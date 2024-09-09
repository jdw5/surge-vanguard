<?php

namespace Conquest\Table\Pagination\Enums;

use Conquest\Table\Table;

enum Paginator: string
{
    case None = 'none';
    case Page = 'page';
    case Cursor = 'cursor';

    public function paginate(Table $table): Paginator
    {
        $builder = $table->getResource();

        return match ($this) {
            self::None => $table->get(),
            self::Cursor => $table->cursorPaginate(
                perPage: 5,
            ),
            default => $table->paginate(),
        };
    }
    /**
     * @
     */
    public function getMeta(Table $table): array
    {
        return match ($this) {
            self::None => [],
            self::Cursor => [],
            default => [

            ]
        };
    }
}
