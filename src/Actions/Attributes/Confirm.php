<?php

namespace Conquest\Table\Actions\Attributes;

use Closure;
use Attribute;
use Conquest\Table\Actions\Confirm\Enums\Intent;
use Conquest\Table\Actions\Confirm\Confirmable;

#[Attribute(Attribute::TARGET_CLASS)]
class Confirm
{
    protected Confirmable $confirm;

    public function __construct(
        string|Closure $title = null,
        string|Closure $description = null,
        string|Intent|Closure $intent = null,
        string|Closure $cancel = null,
        string|Closure $submit = null,
    ) {
        $this->confirm = new Confirmable(func_get_args());
    }

    /**
     * Get the confirm instance.
     *
     * @return \Conquest\Table\Actions\Confirm\Confirmable
     */
    public function getConfirm(): Confirmable
    {
        return $this->confirm;
    }
}

