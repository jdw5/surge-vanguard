<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Http\Request;

/**
 * Trait HasDynamicPagination
 * 
 * This trait is used to add dynamic pagination to the table, which allows for changing
 * the number of items shown per page.
 * 
 * @property string $show
 * @property int $defaultCount
 */
trait HasDynamicPagination
{
    /** String for the key to use */
    protected $showKey;
    /** Default and fallback number to paginate by */
    protected $defaultCount = 10;
    /** Active pagination */
    private $activePagination = null;

    /**
     * Set the options for the dynamic pagination. Should be overriden
     * 
     * @return array
     */
    public function paginationOptions(): array
    {
        return [
            10,
            25,
            50,
            100
        ];
    }

    /**
     * Set the active pagination
     * 
     * @param int|null $count
     */
    public function activePagination(?int $count = null): void
    {
        if (!\is_null($count)) {
            $this->activePagination = $count;
        } else {
            $this->activePagination = $this->getDefaultCount();
        }
    }

    /**
     * Get the active pagination
     * 
     * @return int
     */
    public function getActivePagination(): int
    {
        return $this->activePagination ?? $this->getDefaultCount();
    }

    /**
     * Determine if the pagination is active
     * 
     * @param int $count
     */
    public function isActive(int $count): bool
    {
        return $this->getActivePagination() === $count;
    }

    /**
     * Determine if the table uses dynamic pagination
     * 
     * @return bool
     */
    public function hasDynamicPagination(): bool
    {
        return isset($this->showKey);
    }

    /**
     * Get the key for the dynamic pagination
     * 
     * @return string
     */
    public function getDynamicPaginationKey(): string
    {
        return $this->showKey;
    }

    /**
     * Get the default count for the dynamic pagination
     * 
     * @return int
     */
    public function getDefaultCount(): int
    {
        return $this->defaultCount;
    }

    /**
     * Get the count for the dynamic pagination
     * 
     * @param Request|null $request
     */
    public function getCount(?Request $request): int
    {
        if (!$request) $request = request();
        $this->activePagination($request->query($this->getDynamicPaginationKey(), $this->getDefaultCount()));

        $count = $this->getActivePagination();
        if (!in_array($count, $this->paginationOptions())) {
            $count = $this->getDefaultCount();
        }
        return $count;
    }

    /**
     * Get the pagination options for the table
     * 
     * @return array
     */
    public function getPaginationOptions(): array
    {
        $this->activePagination(request()->query($this->getDynamicPaginationKey(), $this->getDefaultCount()));
        
        return collect($this->paginationOptions())->map(function ($option) {
            return [
                'value' => $option,
                'label' => $option,
                'active' => $this->isActive($option)
            ];
        })->toArray();
    }




}