<?php

namespace Conquest\Table\Actions\Confirm\Proxies;

use Conquest\Core\Contracts\HigherOrder;
use Conquest\Core\Primitive;

/**
 * @internal
 *
 * @mixin Conquest\Table\Actions\Confirm\Confirmable
 *
 * @template T of Conquest\Core\Primitive
 *
 * @template-implements Conquest\Core\Concerns\HigherOrder
 */
class HigherOrderConfirm implements HigherOrder
{
    /**
     * @param  T  $primitive
     */
    public function __construct(
        protected readonly Primitive $primitive
    ) {}

    /**
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
