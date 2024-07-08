<?php

namespace Conquest\Table\DataObjects;

abstract class ActionData
{
    public function __construct(
        public readonly string $type,
        public readonly string $name
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }
}