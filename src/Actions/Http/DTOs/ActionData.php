<?php

namespace Conquest\Table\Actions\Http\DTOs;

class ActionData
{
    public function __construct(
        public readonly string|int $table,
        public readonly string $name,
        public readonly string $type,
    ) {}
}
