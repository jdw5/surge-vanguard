<?php

namespace Conquest\Table\Sorts\Concerns;

use Illuminate\Support\Facades\Request;

trait HasOrder
{
    protected $order;

    public const ASCENDING = 'asc';

    public const DESCENDING = 'desc';

    public function getOrderKey(): string
    {
        if (isset($this->order)) {
            return $this->order;
        }

        return config('table.sorting.order_key', 'order');
    }

    public function setOrderKey(?string $orderKey): void
    {
        if (is_null($orderKey)) {
            return;
        }
        $this->order = $orderKey;
    }

    public function getOrderFromRequest(): ?string
    {
        return Request::input($this->getOrderKey(), null);
    }

    public function sanitiseOrder(?string $value): string
    {
        return in_array($value, [self::ASCENDING, self::DESCENDING]) ? $value : config('table.sorting.default_order', self::ASCENDING);
    }

    public function getOrder(): string
    {
        return $this->sanitiseOrder($this->getOrderFromRequest());
    }
}
