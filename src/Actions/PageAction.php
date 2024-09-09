<?php

namespace Conquest\Table\Actions;

use Conquest\Core\Concerns\HasHttpMethod;
use Conquest\Core\Concerns\HasRoute;
use Conquest\Core\Concerns\Routable;
use Conquest\Table\Table;

class PageAction extends BaseAction
{
    use Routable;

    public function setUp(): void
    {
        $this->setType('page');
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                'route' => $this->getRoute(),
                'method' => $this->getMethod(),
            ]
        );
    }
}
