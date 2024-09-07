<?php

namespace Conquest\Table\Actions\Confirm\Attributes;

use Closure;
use Attribute;
use Conquest\Table\Actions\Confirm\Enums\ConfirmType;
use Conquest\Table\Actions\Confirm\Confirm as Confirmable;

#[Attribute(Attribute::TARGET_CLASS)]
class Confirm
{
    protected ?Confirmable $confirm = null;

    public function __construct(
        string|Closure $title = null,
        string|Closure $description = null,
        string|ConfirmType|Closure $type = null,
        string|Closure $cancel = null,
        string|Closure $submit = null,
    ) {
        $this->confirm = Confirmable::make()->state(func_get_args());
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

