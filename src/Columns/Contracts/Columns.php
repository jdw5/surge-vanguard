<?php

namespace Jdw5\Vanguard\Columns\Contracts;

interface Columns
{
    public function apply(mixed $value);
    public function toArray(): array;
    public function jsonSerialize(): array;
}