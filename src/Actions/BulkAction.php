<?php

namespace Conquest\Table\Actions;

use Conquest\Table\Actions\Concerns\Confirm\Confirms;
use Conquest\Table\Actions\Concerns\CanAction;
use Conquest\Table\Actions\Concerns\Chunking\Chunks;
use Conquest\Table\Actions\Concerns\IsDeselectable;
use Conquest\Table\Actions\Concerns\IsInline;
use Conquest\Table\Table;

class BulkAction extends BaseAction
{
    use Confirms;
    use CanAction;
    use Chunks;
    use IsDeselectable;
    use IsInline;

    public function setUp(): void
    {
        $this->setType(Table::BULK_ACTION);
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            $this->toArrayConfirm(),
            [
                'deselect' => $this->isDeselectable(),
            ]
        );
    }
}
