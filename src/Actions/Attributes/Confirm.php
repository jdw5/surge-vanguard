<?php

namespace Conquest\Table\Actions\Attributes;

use Attribute;
use Closure;
use Conquest\Table\Actions\Confirm\Confirmable;
use Conquest\Table\Actions\Confirm\Enums\Intent;

#[Attribute(Attribute::TARGET_CLASS)]
class Confirm
{
    protected Confirmable $confirm;

    public function __construct(
        string|Closure|null $title = null,
        string|Closure|null $description = null,
        string|Intent|Closure|null $intent = null,
        string|Closure|null $cancel = null,
        string|Closure|null $submit = null,
    ) {
        $this->confirm = new Confirmable(func_get_args());
    }

    /**
     * Get the confirm instance.
     */
    public function getConfirm(): Confirmable
    {
        return $this->confirm;
    }
}
