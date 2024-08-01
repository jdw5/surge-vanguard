<?php

namespace Conquest\Table\Actions;

use Closure;
use Conquest\Table\Actions\Concerns\Confirmation\Confirms;
use Conquest\Table\Actions\Concerns\HasAction;
use Conquest\Table\Actions\Concerns\HasChunking;
use Conquest\Table\Table;

class BulkAction extends BaseAction
{
    use Confirms;
    use HasAction;
    use HasChunking;

    public function setUp(): void
    {
        $this->setType(Table::BULK_ACTION);
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            $this->toArrayConfirm()
        );
    }
}
