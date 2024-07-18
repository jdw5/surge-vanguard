<?php

namespace Conquest\Table\Pagination\Concerns;

use Closure;
use Conquest\Table\Pagination\Pagination;

/**
 * Adds the ability to paginate a table
 *
 * @property string|array $pagination
 */
trait HasPagination
{
    protected static string|Closure $pageTerm = 'page';

    protected static int $defaultPagination = 10;

    protected $pagination;

    public static function setGlobalPageTerm(string|Closure $pageTerm): void
    {
        static::$pageTerm = $pageTerm;
    }


    public function getPageTerm(): string
    {
        return $this->evaluate(static::$pageTerm);
    }

    public static function setGlobalDefaultPagination(int $defaultPagination): void
    {
        static::$defaultPagination = $defaultPagination;
    }

    public function getDefaultPagination(): int
    {
        return static::$defaultPagination;
    }

    protected function setDefaultPagination(int $defaultPagination): void
    {
        $this->defaultPagination = $defaultPagination;
    }

    public function getPagination(): int|array
    {
        if (isset($this->pagination)) {
            return $this->pagination;
        }

        if (method_exists($this, 'pagination')) {
            return $this->pagination();
        }

        return $this->getDefaultPagination();
    }

    protected function setPagination(int|array|null $pagination): void
    {
        if (is_null($pagination)) {
            return;
        }
        $this->pagination = $pagination;
    }

    public function getPaginationOptions(?int $active = null): array
    {
        if (! is_array($this->getPagination())) {
            return [Pagination::make($this->getPagination(), true)];
        }

        $options = [];

        foreach ($this->getPagination() as $count) {
            $options[] = Pagination::make($count, $count === $active);
        }

        return $options;
    }
}
