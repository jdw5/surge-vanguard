<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Jdw5\Vanguard\Table\Pagination\PaginateConfiguration;

/**
 * Trait HasPagination
 * 
 * Adds the ability to paginate a table
 * 
 * @property PaginateConfiguration $paginateConfig
 */
trait HasPagination
{
    protected null|PaginateConfiguration $paginateConfig = null;
    
    /** New API */
    protected $perPage = null;
    protected $pageName = 'page';
    protected $page = null;
    protected $columns = ['*'];

    protected $showKey = 'show';

    /**
     * Set the pagination configuration for standard
     * 
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param int|null $page
     * @return static
     */
    public function paginate($perPage = 10, $columns = ['*'], $pageName = 'page', $page = null): static
    {
        $this->paginateConfig = PaginateConfiguration::paginate([
            'perPage' => $perPage,
            'columns' => $columns,
            'pageName' => $pageName,
            'page' => $page,
        ]);

        return $this;
    }
    /**
     * Set the pagination configuration for cursor
     * 
     * @param int $perPage
     * @param array $columns
     * @param string $cursorName
     * @param int|null $cursor
     * @return static
     */
    public function cursorPaginate($perPage = 10, $columns = ['*'], $cursorName = 'cursor', $cursor = null): static
    {
        $this->paginateConfig = PaginateConfiguration::cursor([
            'perPage' => $perPage,
            'columns' => $columns,
            'pageName' => $cursorName,
            'page' => $cursor,
        ]);
        return $this;
    }

    /**
     * Check if the table is paginated
     * 
     * @return bool
     */
    public function isPaginated(): bool 
    {
        return !\is_null($this->paginateConfig);
    }

    /**
     * Get the pagination configuration
     * 
     * @return PaginateConfiguration|null
     */
    public function paginateType(): string|null
    {
        return $this->paginateConfig->type->value;
    }

    /**
     * Unpack the pagination configuration to an array
     * 
     * @return array
     */
    public function unpackPaginateToArray(): mixed
    {
        return [
            'perPage' => $this->paginateConfig->perPage,
            'pageName' => $this->paginateConfig->pageName,
            'page' => $this->paginateConfig->page,
            'columns' => $this->paginateConfig->columns,
        ];
    }

    // public function 
    public function definePagination(): mixed
    {
        return 10;
    }


    
}