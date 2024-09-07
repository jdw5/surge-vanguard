<?php

namespace Conquest\Table\Actions\Confirm\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Confirm
{
    public function __construct(
        public string $title,
        public string $description,
        public string $type,
        public string $cancelText,
        public string $submitText,
    ) {}
}

