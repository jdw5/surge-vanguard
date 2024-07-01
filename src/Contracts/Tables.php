<?php

namespace Conquest\Table\Contracts;

interface Tables
{
    public function getMeta(): array;

    public function getRecords(): ?array;
}
