<?php

namespace Jdw5\Vanguard\Table\Concerns;

trait HasExports
{
    protected array $exports;

    protected function setExports(array|null $exports): void
    {
        if (is_null($exports)) return;
        $this->exports = $exports;
    }
    
    protected function getExports(): array
    {
        if (isset($this->exports)) {
            return $this->exports;
        }

        if (function_exists('exports')) {
            return $this->exports();
        }

        return [];
    }
}