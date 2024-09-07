<?php

namespace Conquest\Table\Actions\Confirm;

use Conquest\Core\Contracts\HigherOrder;
use Conquest\Table\Actions\Concerns\CanBeConfirmable;

class HigherOrderConfirm implements HigherOrder
{
    const PROPERTY = 'confirm';

    public function __construct(
        protected readonly CanBeConfirmable $primitive
    ) {}

    public function __call(string $name, array $arguments): CanBeConfirmable
    {
        $this->primitive->setConfirm(true);

        $this->primitive->{self::PROPERTY}->{$name}($arguments);

        return $this->primitive;
    }
}