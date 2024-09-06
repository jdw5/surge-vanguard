<?php

namespace Conquest\Table\Actions\Confirm;

use Conquest\Core\Contracts\HigherOrder;

class HigherOrderConfirm implements HigherOrder
{
    public function __construct(protected Confirm $confirm) {}

    public function __call($method, $parameters)
    {
        return $this->confirm->{$method}(...$parameters);
    }

}
