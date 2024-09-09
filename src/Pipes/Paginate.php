<?php

namespace App\Table\Pipes;

use Closure;
use Conquest\Table\Pagination\Enums\Paginator;
use Conquest\Table\Pipes\Contracts\Paginates;
use Conquest\Table\Table;

/**
 * @internal
 */
class Paginate implements Paginates
{
    public function handle(Table $table, Closure $next)
    {
        $builder = $table->getResource();
        /** @var array{records: \Illuminate\Support\Collection, meta: array} $data */
        $data = match ($table->getPaginateType()) {
            Paginator::Cursor => [
                $data = $builder->cursorPaginate(
                    perPage: $table->usePerPage(),
                    cursorName: $table->getPageName(),
                )->withQueryString(),
                $this->getCursorMeta($data),
            ],
            Paginator::None => [
                $data = $builder->get(),
                $this->getCollectionMeta($data),
            ],
            default => [
                $data = $builder->paginate(
                    perPage: $this->usePerPage(),
                    pageName: $this->getPageName(),
                )->withQueryString(),
                $this->getPaginateMeta($data),
            ],
        };

        return $next($table);
    }
}
