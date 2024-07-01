<?php

namespace Conquest\Table\Columns\Contracts;

interface Columns
{
    public function apply(mixed $value);

    public function toArray(): array;
}
