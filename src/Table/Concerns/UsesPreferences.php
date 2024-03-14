<?php

namespace Jdw5\Vanguard\Table\Concerns;

use Illuminate\Http\Request;
use Jdw5\Vanguard\Table\Columns\Column;

trait UsesPreferences
{
    protected $preferences;
    protected $preferencesName = 'cols';
    protected $cachedPreferences = null;

    public function hasPreferences(): bool
    {
        return isset($this->preferences) && $this->preferences;
    }

    public function updatePreferences(?Request $request = null): array
    {
        if (\is_null($request)) {
            $request = request();
        }

        if ($request->has($this->preferencesName)) {
            return explode(',', $request->get($this->preferencesName));
        }
        return [];
    }

    public function getPreferences(): array
    {
        return $this->cachedPreferences ??= $this->updatePreferences();
    }
}