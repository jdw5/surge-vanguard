<?php

namespace Conquest\Table\Actions;

use Conquest\Table\Actions\BaseAction;

class BulkAction extends BaseAction
{
    // Needs to have a handler
    public function toArray(): array
    {
        return array_merge(parent::jsonSerialize(), $this->serializeStaticEndpoint());
    }
}
