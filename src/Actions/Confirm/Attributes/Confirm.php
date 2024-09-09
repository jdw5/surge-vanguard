<?php

namespace Conquest\Table\Actions\Confirm\Attributes;

use Closure;
use Attribute;
use Conquest\Table\Actions\Confirm\Enums\Intent;
use Conquest\Table\Actions\Confirm\Confirm as Confirmable;

#[Attribute(Attribute::TARGET_CLASS)]
class Confirm
{
    protected ?Confirmable $confirm = null;

    public function __construct(
        string|Closure $title = null,
        string|Closure $description = null,
        string|Intent|Closure $type = null,
        string|Closure $cancel = null,
        string|Closure $submit = null,
    ) {
        $this->confirm = new Confirmable(func_get_args());
    }

    /**
     * Get the confirm instance.
     *
     * @return \Conquest\Table\Actions\Confirm\Confirm|null
     */
    public function getConfirm(): ?Confirmable
    {
        return $this->confirm;
    }
}

