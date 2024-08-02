<?php

namespace Conquest\Table\Actions;

use Conquest\Table\Actions\Concerns\Confirmation\Confirms;
use Conquest\Table\Actions\Concerns\CanAction;
use Conquest\Table\Actions\Concerns\HasChunking;
use Conquest\Table\Actions\Concerns\IsDeselectable;
use Conquest\Table\Actions\Concerns\IsInline;
use Conquest\Table\Table;

class BulkAction extends BaseAction
{
    use Confirms;
    use CanAction;
    use HasChunking;
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
