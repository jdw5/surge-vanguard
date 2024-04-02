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
    private $perPage = null;
    private $activeDynamicOption;
    
    protected $pageName = 'page';
    protected $page = null;
    protected $columns = ['*'];
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
        return $this;
    }

    public function get(): static
    {
        $this->paginateType = 'get';
        return $this;
    }
    
    public function dynamicPaginate(array $perPages, string $showKey = 'show'): static
    {
        $this->perPage = $perPages;
        $this->showKey = $showKey;
        $this->paginateType = 'dynamic';
        return $this;
    }

    public function hasDynamicPagination(): bool
    {
        return $this->paginateType === 'dynamic' || is_array($this->definePagination());
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

    public function showKey(): string
    {
        return $this->showKey;
    }

    private function hasBeenOverriden(): bool
    {
        return !\is_null($this->paginateType());
    }

    public function getDynamicPerPage(): int
    {
        if (isset($this->activeDynamicOption)) return $this->activeDynamicOption;
        $value = request()->query($this->showKey);

        $this->activeDynamicOption = intval($value);

        if (\is_null($this->activeDynamicOption) || !in_array($this->activeDynamicOption, $this->definePagination())) {
            $this->activeDynamicOption = $this->defaultPerPage;
        }
        return $this->activeDynamicOption;

    }

    public function perPage(): int
    {
        if ($this->hasBeenOverriden()) {
            return $this->paginateType() !== 'dynamic' ? $this->perPage : $this->getDynamicPerPage();
        }

        // Otherwise, use the definePagination to determine the type
        $this->perPage = $this->definePagination();

        if (\is_array($this->perPage)) {
            return $this->getDynamicPerPage();
        }
        return $this->perPage;

    }

    public function pageName(): string
    {
        return $this->pageName;
    }

    public function page(): mixed
    {
        return $this->page;
    }

    public function columns(): array
    {
        return $this->columns;
    }

    public function getPagination(): array
    {
        // The application defaults to pagination with the defaultPerPage if nothing is provided
        return [
            'perPage' => $this->perPage(),
            'pageName' => $this->pageName(),
            'page' => $this->page(),
            'columns' => $this->columns(),
        ];
    }

    /**
     * Should be used to override the perPage metric
     * 
     * @return int|array
     */
    public function definePagination()
    {
        return 10;
    }

    public function getPaginationOptions(): array
    {
        $options = $this->paginateType() === 'dynamic' ? $this->perPage : $this->definePagination();

        return collect($options)->map(function ($value) {
            return [
                'value' => $value,
                'label' => $value,
                'active' => $value ===  $this->getDynamicPerPage(),
            ];
        })->values()->toArray();
    }   
}