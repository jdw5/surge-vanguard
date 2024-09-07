<?php

namespace Conquest\Table\Actions\Confirm\Attributes;

use Attribute;
use Conquest\Table\Actions\Confirm\Confirm as Confirmable;

#[Attribute(Attribute::TARGET_CLASS)]
class Confirm
{
    protected Confirmable|null $confirm = null;

    public function __construct(
        string $title = null,
        string $description = null,
        string $type = null,
        string $cancel = null,
        string $submit = null,
    ) {
        $this->confirm = Confirmable::make()->state(func_get_args());
    }

    public function getConfirm(): ?Confirmable
    {
        return $this->confirm;
    }
}

