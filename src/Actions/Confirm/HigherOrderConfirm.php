<?php

namespace Conquest\Table\Actions\Confirm;

use Conquest\Core\Primitive;
use Conquest\Core\Contracts\HigherOrder;
use Conquest\Table\Actions\Concerns\CanBeConfirmable;

class HigherOrderConfirm implements HigherOrder
{
    /**
     * @param Primitive&CanBeConfirmable $primitive
     */
    public function __construct(
        protected readonly Primitive $primitive
    ) {

    }

    public function __call(string $name, array $arguments): Primitive
    {
        $this->primitive->setConfirm(true);

        $confirm = $this->primitive->getConfirm();
        if ($confirm && method_exists($confirm, $name)) {
            $confirm->{$name}(...$arguments);
        }

        return $this->primitive;
    }
}