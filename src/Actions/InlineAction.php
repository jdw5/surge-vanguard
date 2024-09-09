<?php

declare(strict_types=1);

namespace Conquest\Table\Actions;

use Conquest\Core\Concerns\IsDefault;
use Conquest\Core\Concerns\Routable;
use Conquest\Core\Contracts\HigherOrder;
use Conquest\Core\Contracts\ProxiesHigherOrder;
use Conquest\Table\Actions\Concerns\CanAction;
use Conquest\Table\Actions\Concerns\CanBeConfirmable;
use Conquest\Table\Actions\Concerns\IsBulk;
use Conquest\Table\Actions\Confirm\Proxies\HigherOrderConfirm;

/**
 * @property-read \Conquest\Table\Actions\Confirm\Confirm $confirm
 */
class InlineAction extends BaseAction implements ProxiesHigherOrder
{
    use CanAction;
    use CanBeConfirmable;
    use IsBulk;
    use IsDefault;
    use Routable;

    public function setUp(): void
    {
        $this->setType('inline');
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                'route' => $this->getRoute(),
                'method' => $this->getMethod(),
                'actionable' => $this->canAction(),
                'confirm' => $this->getConfirm()?->toArray(),
            ]
        );
    }

    public function __get(string $property): HigherOrder
    {
        return match ($property) {
            'confirm' => new HigherOrderConfirm($this),
            default => throw new \Exception("Property [{$property}] does not exist on ".self::class),
        };
    }
}
