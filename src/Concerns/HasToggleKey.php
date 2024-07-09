<?php

namespace Conquest\Table\Concerns;

use Illuminate\Http\Request;

trait HasToggleKey
{
    protected string $toggleKey = 'cols';

    public static function setGlobalToggleKey(string $key): void
    {
        static::$toggleKey = $key;
    }

    protected function setToggleKey(string|null $key): void
    {
        if (is_null($key)) return;
        $this->toggleKey = $key;
    }

    public function getToggleKey(): string
    {
        if (method_exists($this, 'toggleKey')) {
            return $this->toggleKey();
        }
        return $this->toggleKey;
    }

    /**
     * Retrieve the column names which are active for toggling
     */
    public function getActiveToggles(Request $request): array
    {
        return explode(',', $request->query($this->getToggleKey(), ''));
    }
}
