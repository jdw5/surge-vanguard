<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Http\Request;
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
    private $cachedPreferences = [];

    public abstract function definePreferenceKey(): string;
    public abstract function definePreferenceCookie(): string;

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
    public function preferences(): string
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
        
        $preferencedColumns = $request->query($this->dynamicName());
        
        if (!\is_null($preferencedColumns)) $preferencedColumns = str_getcsv($preferencedColumns);
        
        if ($this->usesPreferenceCookie() && $request->hasCookie($this->preferenceCookie()) && is_null($preferencedColumns)) {
            $preferencedColumns = $request->cookie($this->preferenceCookie());
        } else if (count($preferencedColumns) > 0) {
            Cookie::queue($this->preferenceCookie(), $preferencedColumns, 60 * 24 * 30);
        } else {
            $preferencedColumns = [];
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
}