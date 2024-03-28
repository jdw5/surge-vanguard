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

    private bool $set = false;
    protected $defaultPerPage = 10;
    protected $paginateType = null;
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
    public function paginate(int $perPage = 10, array $columns = ['*'], string $pageName = 'page', $page = null): static
    {
        $this->perPage = $perPage;
        $this->columns = $columns;
        $this->pageName = $pageName;
        $this->page = $page;
        $this->paginateType = 'paginate';
        $this->set();
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
    public function cursorPaginate(int $perPage = 10, array $columns = ['*'], string $cursorName = 'cursor', $cursor = null): static
    {
        $this->perPage = $perPage;
        $this->columns = $columns;
        $this->pageName = $cursorName;
        $this->page = $cursor;
        $this->paginateType = 'paginate';
        $this->set();
        return $this;
    }

    public function get(): static
    {
        $this->paginateType = null;
        $this->set();
        return $this;
    }

    
    public function dynamicPaginate(array $perPages, string $showKey = 'show'): static
    {
        $this->perPage = $perPages;
        $this->showKey = $showKey;
        $this->paginateType = 'dynamic';
        $this->set();
        return $this;
    }
    
    private function set(): void
    {
        $this->set = true;
    }
    /**
     * Check if the table is paginated
     * 
     * @return bool
     */
    public function hasPagination(): bool 
    {
        return !\is_null($this->paginateType) && $this->set;
    }

    /**
     * Get the pagination configuration
     * 
     * @return PaginateConfiguration|null
     */
    public function paginateType(): string|null
    {
        return $this->paginateType;
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

    public function getDynamicPerPage()
    {
        $request = request();
        // Retrieve the show search query parameter

        // Verify it's in perPages; prevent users from controlling. If it isn't, then, return the defaultPerPage

    }

    public function perPage()
    {

        if ($this->set() && $this->paginateType() !== 'dynamic') {
            return $this->perPage;
        }
        if ($this->paginateType() === 'dynamic') {
            return $this->getDynamicPerPage();
        }
        return $this->perPage;
    }

    public function getPagination(): array
    {
        // The application defaults to pagination with the defaultPerPage if nothing is provided
        return [
            'perPage' => $this->perPage,
            'pageName' => $this->pageName,
            'page' => $this->page,
            'columns' => $this->columns,
        ];
    }

    /**
     * Should be used to override the perPage metric
     */
    public function definePagination(): mixed
    {
        return 10;
    }


    
}