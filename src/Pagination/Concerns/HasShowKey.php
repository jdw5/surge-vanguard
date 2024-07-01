<?php

namespace Conquest\Table\Pagination\Concerns;

trait HasShowKey
{
    protected string $showKey;

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
        if (isset($this->showKey)) {
            return $this->showKey;
        }

        if (method_exists($this, 'showKey')) {
            return $this->showKey();
        }

        return 'show';
    }
}
