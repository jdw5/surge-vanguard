<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Jdw5\Vanguard\Table\Columns\Column;

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
    protected $cookieDuration = 60 * 24 * 30;

    protected function definePreferenceKey()
    {
        return null;
    }

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
        return !\is_null($this->preferences());
    }

    public function usesPreferenceCookie(): bool
    {
        return !\is_null($this->getPreferenceCookie());
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

    public function getPreferenceCookie(): string|null
    {
        return $this->evaluate($this->preferenceCookie ?? $this->definePreferenceCookie());
    }

    protected function getCookie(Request $request): ?string
    {
        if ($this->usesPreferenceCookie() && $request->hasCookie($this->getPreferenceCookie())) {
            return $request->cookie($this->getPreferenceCookie());
        }
        return null;
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
        if (!$this->hasPreferences()) return [];
        
        $preferencedColumns = $request->query($this->preferences());
        
        if (!empty($preferencedColumns)) $preferencedColumns = str_getcsv($preferencedColumns);
        else $preferencedColumns = [];

        if ($this->usesPreferenceCookie() && $request->hasCookie($this->getPreferenceCookie()) && \is_null($preferencedColumns)) {
            $preferencedColumns = json_decode($request->cookie($this->getPreferenceCookie()));
        } else if ($this->usesPreferenceCookie() && count($preferencedColumns) > 0) {
            Cookie::queue($this->preferenceCookie(), json_encode($preferencedColumns), $this->cookieDuration);
        }
        
        /** It should then find the defaults */
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
        return $cols->map(fn ($col) => [
            'name' => $col->getName(),
            'label' => $col->getLabel(),
            'active' => $col->shouldBeDynamicallyShown($this->getPreferences())
        ])->values()->toArray();
    }
}