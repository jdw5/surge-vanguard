<?php

namespace Jdw5\Vanguard\Table\Actions;

use Jdw5\Vanguard\Table\Actions\BaseAction;
use Jdw5\Vanguard\Table\Actions\Concerns\HasEndpoint;

class PageAction extends BaseAction
{    
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), $this->serializeStaticEndpoint());
    }
}
