<?php

namespace Jdw5\Vanguard\Table\Concerns;

trait HasExports
{
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