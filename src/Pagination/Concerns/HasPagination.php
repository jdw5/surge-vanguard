<?php

namespace Conquest\Table\Concerns;

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
    protected int|array $pagination = 10;

    public static function setGlobalPagination(int|array $pagination): void
    {
        static::$pagination = $pagination;
    }
    
    /** Create a registered method which defines default at root */

    protected function setPagination(int|array|null $pagination): void
    {
        if (is_null($pagination)) return;
        $this->pagination = $pagination;
    }

    /**
     * Should be used to override the perPage metric
     * 
     * @return int|array
     */
    protected function getRawPagination()
    {
        if (method_exists($this, 'pagination')) {
            return $this->pagination();
        }
        return $this->pagination;
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