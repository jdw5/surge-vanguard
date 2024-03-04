<?php

namespace Jdw5\Vanguard\Table\Actions;

use Jdw5\Vanguard\Table\Actions\BaseAction;
use Jdw5\Vanguard\Table\Actions\Concerns\HasEndpoint;

class PageAction extends BaseAction
{
    use HasEndpoint;

    public function jsonSerialize(): mixed
    {
        return array_merge(
            parent::jsonSerialize(),
            [
                'has_endpoint' => $this->hasEndpoint(),
                'endpoint' => $this->hasEndpoint() ? [
                    'method' => $this->getMethod(),
                    'route' => $this->getEndpoint(),
                ] : null
            ]
        );
    }
    
}
