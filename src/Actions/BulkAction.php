<?php

namespace Jdw5\Vanguard\Actions;

use Jdw5\Vanguard\Actions\BaseAction;

class BulkAction extends BaseAction
{
    // Needs to have a handler
    public function toArray(): array
    {
        return array_merge(parent::jsonSerialize(), $this->serializeStaticEndpoint());
    }
}
