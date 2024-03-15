<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Http\Request;
use Jdw5\Vanguard\Table\Columns\Column;

/**
 * Trait HasDynamics
 * 
 * Add the ability to allow users to select what columns are retrieved
 * 
 * @property bool $dynamic
 * @property string $dynamicName
 */
trait HasDynamics
{
    /** Must be set to true to enable dynamic columns */
    protected $dynamic; 
    /** Set the name of the query param */
    protected $dynamicName = 'cols';
    private $cachedDynamicCols = [];

    /**
     * Check if dynamics are applied
     * 
     * @return bool
     */
    public function dynamicsApplied(): bool
    {
        return $this->hasDynamicCols() && count($this->getDynamicCols()) > 0;
    }

    /**
     * Check if the table has dynamic columns
     * 
     * @return bool
     */
    public function hasDynamicCols(): bool
    {
        return isset($this->dynamic) && $this->dynamic;
    }

    /**
     * Get the name of the dynamic column for search query purposes
     * 
     * @return string
     */
    public function dynamicName(): string
    {
        return $this->evaluate($this->dynamicName);
    }

    /**
     * Update the dynamic actives from search query
     * 
     * @param Request|null $request
     * @return array
     */
    public function updateDynamicActives(?Request $request = null): array
    {
        if (\is_null($request)) {
            $request = request();
        }

        if ($request->has($this->dynamicName())) {
            return explode(',', $request->query($this->dynamicName()));
        }
        return [];
    }

    /**
     * Get the dynamic columns
     * 
     * @return array
     */
    public function getDynamicCols(): array
    {
        return $this->cachedDynamicCols ??= $this->updateDynamicActives();
    }
}