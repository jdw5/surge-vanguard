<?php

namespace Conquest\Table\Contracts;

interface Columns
{
    public function apply(mixed $value);

    public function toArray(): array;
}
