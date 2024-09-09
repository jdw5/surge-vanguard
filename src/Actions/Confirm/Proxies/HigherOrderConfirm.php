<?php

namespace Conquest\Table\Actions\Confirm\Proxies;

use Conquest\Core\Primitive;
use Conquest\Core\Contracts\HigherOrder;
use Conquest\Table\Actions\Concerns\CanBeConfirmable;

/**
 * @internal
 * @mixin Conquest\Table\Actions\Confirm\Confirm
 * @template T of Conquest\Core\Primitive
 * @template-implements Conquest\Core\Concerns\HigherOrder
 */
class HigherOrderConfirm implements HigherOrder
{
    /**
     * @param T $primitive
     */
    public function __construct(
        protected readonly Primitive $primitive
    ) {

    }

    /**
     * @param string $name
     * @param array $arguments
     * @return T
     */
    public function __call(string $name, array $arguments)
    {
        $this->primitive->setConfirm(true);

        $confirm = $this->primitive->getConfirm();
        if ($confirm && method_exists($confirm, $name)) {
            $confirm->{$name}(...$arguments);
        }

        return $this->primitive;
    }
}