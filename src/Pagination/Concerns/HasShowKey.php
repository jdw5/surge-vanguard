<?php

namespace Conquest\Table\Pagination\Concerns;

trait HasShowKey
{
    protected static string $showKey;

    public static function setGlobalShowKey(string $showKey): void
    {
        static::$showKey = $showKey;
    }

    protected function setShowKey(string $showKey): void
    {
        $this->showKey = $showKey;
    }

    public function getShowKey(): string
    {
        if (isset(static::$showKey)) {
            return static::$showKey;
        }

        if (method_exists($this, 'showKey')) {
            return $this->showKey();
        }

        return 'show';
    }
}
