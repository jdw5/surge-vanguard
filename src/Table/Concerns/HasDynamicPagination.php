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
        $count = $request->query($this->getDynamicPaginationKey(), $this->getDefaultCount());

        if (!in_array($count, $this->paginationOptions())) {
            $count = $this->getDefaultCount();
        }
        return $count;
    }




}