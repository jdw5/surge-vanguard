<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

/**
 * Trait HasPreferences
 * 
 * Add the ability to allow users to select what columns are retrieved
 * 
 * @property bool $dynamic
 * @property string $dynamicName
 */
trait HasPreferences
{
    /** Set the name of the query param */
    protected $preferences = null;
    protected $preferenceCookie = null;
    private $cachedPreferences = null;

    public function definePreferenceKey()
    {
        return null;
    }

    public function definePreferenceCookie()
    {
        return null;
    }

    /**
     * Check if the table has dynamic columns
     * 
     * @return bool
     */
    public function hasPreferences(): bool
    {
        return !\is_null($this->preferences());
    }

    public function usesPreferenceCookie(): bool
    {
        return !\is_null($this->preferenceCookie());
    }

    /**
     * Get the name of the dynamic column for search query purposes
     * 
     * @return string
     */
    public function preferences(): ?string
    {
        return $this->evaluate($this->preferences ?? $this->definePreferenceKey());
    }

    public function preferenceCookie(): string
    {
        return $this->evaluate($this->preferenceCookie ?? $this->definePreferenceCookie());
    }

    /**
     * Update the dynamic actives from search query
     * 
     * @param Request|null $request
     * @return array
     */
    public function getUpdatedPreferences(?Request $request = null): array
    {
        if (\is_null($request)) $request = request();
        
        $preferencedColumns = $request->query($this->preferences());
        
        if (!\is_null($preferencedColumns)) $preferencedColumns = str_getcsv($preferencedColumns);
        else $preferencedColumns = [];


        if ($this->usesPreferenceCookie() && $request->hasCookie($this->preferenceCookie()) && is_null($preferencedColumns)) {
            $preferencedColumns = json_decode($request->cookie($this->preferenceCookie()));
        } else if (count($preferencedColumns) > 0) {
            Cookie::queue($this->preferenceCookie(), json_encode($preferencedColumns), 60 * 24 * 30);
        }
        return $preferencedColumns;
    }
    
    /**
     * Get the dynamic columns
     * 
     * @return array
     */
    public function getPreferences(): array
    {
        return $this->cachedPreferences ??= $this->getUpdatedPreferences();
    }

    public function getPreferenceColumns(Collection $cols): array
    {
        return $cols->map(fn ($column) => [
            'name' => $column->getName(),
            'label' => $column->getLabel(),
            'active' => in_array($column->getName(), $this->getPreferences()),
        ])->values()->toArray();
    }
}