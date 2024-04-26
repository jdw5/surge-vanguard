<?php

namespace Jdw5\Vanguard\Table\Concerns;

/**
 * Trait HasPagination
 * 
 * Adds the ability to paginate a table
 * 
 */
trait HasPagination
{
    /** New API */
    private $activeDynamicOption;
    protected $defaultPerPage = 10;
    protected $showKey = 'show';
    protected $pageName = 'page';
    protected $perPage = null;
    protected $page = null;
    protected $paginateType = null;
    
    protected $columns = ['*'];

    /**
     * Should be used to override the perPage metric
     * 
     * @return int|array
     */
    protected function definePagination()
    {
        return 10;
    }

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
        $this->setPerPage($perPage); // set the perPage value
        $this->setColumns($columns); // set the columns to be selected
        $this->setPage($page); // set the current page
        $this->setPageName($pageName); // set the page name
        $this->setPaginateType('paginate'); // set the pagination type
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
        $this->setPerPage($perPage);
        $this->setColumns($columns);
        $this->setPage($cursor);
        $this->setPageName($cursorName); 
        $this->setPaginateType('cursor'); 
        return $this;
    }

    public function get(): static
    {
        $this->setPaginateType('get');
        return $this;
    }
    
    public function dynamicPaginate(array $perPages, string $showKey = 'show'): static
    {
        $this->setPerPage($perPages);
        $this->setShowKey($showKey);
        $this->setPaginateType('dynamic');
        return $this;
    }

    ############################################################################################
    # Internal methods
    ############################################################################################
    public function hasDynamicPagination(): bool
    {
        return $this->isDynamic() || \is_array($this->definePagination());
    }

    private function hasBeenOverriden(): bool
    {
        return !\is_null($this->getPaginateType());
    }

    public function getDynamicPerPage(): int
    {
        if (isset($this->activeDynamicOption)) return $this->activeDynamicOption;
        $value = request()->query($this->showKey);

        $this->activeDynamicOption = \intval($value);

        if (\is_null($this->activeDynamicOption) || !in_array($this->activeDynamicOption, $this->definePagination())) {
            $this->activeDynamicOption = $this->defaultPerPage;
        }
        return $this->activeDynamicOption;

    }

    public function getPagination(): array
    {
        // The application defaults to pagination with the defaultPerPage if nothing is provided
        return [
            'perPage' => $this->getPerPage(),
            'pageName' => $this->getPageName(),
            'page' => $this->getPage(),
            'columns' => $this->getColumns(),
        ];
    }

    public function getPaginationOptions(): array
    {
        $options = $this->isDynamic() ? $this->perPage : $this->definePagination();

        return collect($options)->map(function ($value) {
            return [
                'value' => $value,
                'label' => $value,
                'active' => $value === $this->getDynamicPerPage(),
            ];
        })->values()->toArray();
    }   

    public function serializePagination(): array
    {
        return $this->hasDynamicPagination() ? 
            [
                'paging_options' => 
                [
                    'options' => $this->getPaginationOptions(),
                    'key' => $this->getShowKey()
                ]
            ] : [];
    }

    /**
     * Set the perPage value
     * 
     * @param int|array $perPage
     * @return void
     */
    protected function setPerPage(int|array $perPage): void
    {
        $this->perPage = $perPage;
    }

    /**
     * Get the perPage value
     * 
     * @return int
     */
    protected function getPerPage(): mixed
    {

        if ($this->hasBeenOverriden()) {
            return !$this->isDynamic() ? $this->perPage : $this->getDynamicPerPage();
        }

        // Otherwise, use the definePagination to determine the type
        $this->setPerPage($this->definePagination());

        
        if (\is_array($this->perPage)) {
            return $this->getDynamicPerPage();
        }

        return $this->perPage;
    }

    /**
     * Set the type of pagination
     * 
     * @param string $paginateType
     */
    protected function setPaginateType(string $paginateType): void
    {
        if (!in_array($paginateType, ['paginate', 'cursor', 'get', 'dynamic'])) {
            throw new \InvalidArgumentException('Invalid pagination type');
        }

        $this->paginateType = $paginateType;
    }

    /**
     * Get the pagination configuration
     * 
     * @return string|null
     */
    public function getPaginateType(): ?string
    {
        return $this->paginateType;
    }

    /**
     * Get the current page
     */
    protected function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * Set the current page
     * 
     * @param int|null $page
     */
    protected function setPage(?int $page): void
    {
        $this->page = $page;
    }

    /**
     * Get the key used for dynamic pagination in query string
     * 
     * @return string
     */
    protected function getShowKey(): ?string
    {
        return isset($this->showKey) ? $this->showKey : null;
    }

    protected function setShowKey(string $showKey): void
    {
        $this->showKey = $showKey;
    }

    /**
     * Set the columns to be selected for pagination
     * 
     * @param array $columns
     * @return void
     */
    protected function setColumns(array $columns): void
    {
        $this->columns = $columns;
    }

    /**
     * Get the columns to be selected for pagination
     * 
     * @return array
     */
    protected function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Set the page name
     * 
     * @param array $columns
     * @return void
     */
    protected function setPageName(string $pageName): void
    {
        $this->pageName = $pageName;
    }

    /**
     * Get the page name
     * 
     * @return array
     */
    protected function getPageName(): string
    {
        return $this->pageName;
    }

    /**
     * Determine if the pagination is dynamic
     * 
     * @return bool
     */
    private function isDynamic(): bool
    {
        return $this->getPaginateType() === 'dynamic';
    }
}