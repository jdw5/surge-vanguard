<?php

namespace Conquest\Table\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Conquest\Table\Columns\Column;

/**
 * Trait HasPreferences
 * 
 * Add the ability to allow users to select what columns are shown.
 *
 */
trait HasPreferences
{
    /** The search query parameter name */
    protected $preferences = null;
    /** The cookie name for storing preferences */
    protected $preferenceCookie = null;
    /** The duration of the cookie */
    protected $cookieDuration = 60 * 24 * 30;
    
    private $cachedPreferences = null;

    /**
     * Define the key for the dynamic columns in the query parameters
     * 
     * @return string
     */
    protected function definePreferenceKey()
    {
        return null;
    }

    /**
     * Define the unique cookie name if you intend on storing user preferences for this table
     * 
     * @return string
     */
    protected function definePreferenceCookie()
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
        return ! \is_null($this->getPreferenceKey());
    }

    /**
     * Check if the table uses a preference cookie
     * 
     * @return bool
     */
    public function hasPreferenceCookie(): bool
    {
        return !\is_null($this->getPreferenceCookie());
    }

    /**
     * Check if the table uses a preference cookie
     * 
     * @return bool
     */
    public function usesPreferenceCookie(): bool
    {
        return !\is_null($this->getPreferenceCookie());
    }

    /**
     * Get the name of the dynamic column for search query purposes
     * 
     * @return string if preferencing enabled
     * @return null if not enabled
     */
    public function getPreferenceKey(): ?string
    {
        return $this->evaluate($this->preferences ?? $this->definePreferenceKey());
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

    /**
     * Get the name of the cookie if it exists
     * 
     * @return string if cookie preferencing enabled
     * @return null if not enabled
     */
    public function getPreferenceCookie(): ?string
    {
        return $this->evaluate($this->preferenceCookie ?? $this->definePreferenceCookie());
    }

    /**
     * Retrieve the cookie value if it exists from the request
     * 
     * @param Request $request
     * @return string|null
     */
    public function getCookie(Request $request): ?string
    {
        if ($this->hasActiveCookie($request)) return $request->cookie($this->getPreferenceCookie());
        return null;
    } 

    /**
     * Check if the current request has the table cookie
     * 
     * @param Request $request
     * @return bool
     */
    public function hasActiveCookie(Request $request): bool
    {
        return $this->hasPreferenceCookie() && $request->hasCookie($this->getPreferenceCookie());
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

        // If the table does not have preferences, return an empty array
        if (!$this->hasPreferences()) return [];
        
        // Retrieve the value. It should be in form key=a,b,c
        $preferencedColumns = $request->query($this->getPreferenceKey());
        
        // Create an array based on the search params
        if (!empty($preferencedColumns)) $preferencedColumns = str_getcsv($preferencedColumns);
        else $preferencedColumns = [];

        // Case 1: Cookies enabled, cookie exists and there are no preferenced cols -> get preferences from cookie
        if ($this->hasActiveCookie($request) && !count($preferencedColumns)) {
            $preferencedColumns = json_decode($request->cookie($this->getPreferenceCookie()));
        } 
        // Case 2: Cookies are enabled and there are preferenced columns -> update cookie with json encoded cols
        else if ($this->hasPreferenceCookie() && count($preferencedColumns)) {
            Cookie::queue($this->getPreferenceCookie(), json_encode($preferencedColumns), $this->getCookieDuration());
        }
        // Case 3: No cookies, rely on params and/or defaults
        
        // Return the cols. If the array is empty, then the preference defaults should be used
        return $preferencedColumns;
    }

    /**
     * Get the cookie duration in seconds
     * 
     * @return int
     */
    public function getCookieDuration(): int
    {
        return $this->cookieDuration;
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

    /**
     * Get the columns with preference status
     * 
     * @param Collection $allCols
     * @return array
     */
    public function getColumnsWithPreferences(Collection $allCols): array
    {
        return $allCols->map(fn ($col) => [
            'name' => $col->getName(),
            'label' => $col->getLabel(),
            'active' => $col->shouldBeDynamicallyShown($this->getPreferences()),
            'fixed' => $col->isPreferable(),
        ])->values()->toArray();
    }
}