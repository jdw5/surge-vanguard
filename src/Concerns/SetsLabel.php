<?php

namespace Jdw5\Vanguard\Concerns;

trait SetsLabel
{
    protected function nameToLabel(string $name): string
    {
        return str($name)->headline()->lower()->ucfirst();
    }
}