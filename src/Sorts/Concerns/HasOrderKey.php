<?php

namespace Jdw5\Vanguard\Sorts\Concerns;

trait HasOrderKey
{
    protected string $order = 'order';

    public static function setGlobalOrderKey(string $order): void
    {
        static::$order = $order;
    }

    public function getOrderKey(): string
    {
        return $this->order;
    }
}