<?php

namespace Conquest\Table\Sorts\Concerns;

trait HasOrderKey
{
    const array VALID_ORDERS = ['asc', 'desc'];

    protected string $order = 'order';

    protected string $defaultDirection = 'asc';

    public static function setGlobalDefaultDirection(string $defaultDirection): void
    {
        if (! in_array($defaultDirection, self::VALID_ORDERS)) {
            return;
        }

        static::$defaultDirection = $defaultDirection;
    }

    public static function setGlobalOrderKey(string $order): void
    {
        static::$order = $order;
    }

    public function getOrderKey(): string
    {
        return $this->order;
    }

    public function sanitiseOrder(?string $value): string
    {
        return in_array($value, static::VALID_ORDERS) ? $value : $this->getDefaultDirection();
    }

    public function getDefaultDirection(): string
    {
        return $this->evaluate($this->defaultDirection);
    }
}
