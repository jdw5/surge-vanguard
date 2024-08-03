<?php

namespace Conquest\Table\Actions;

use Conquest\Table\Table;
use Conquest\Table\Actions\Concerns\Confirm\Confirms;
use Conquest\Table\Actions\Concerns\CanAction;
use Conquest\Table\Actions\Concerns\Chunk\Chunks;
use Conquest\Table\Actions\Concerns\IsDeselectable;
use Conquest\Table\Actions\Concerns\IsInline;

class BulkAction extends BaseAction
{
    use Confirms;
    use CanAction;
    use Chunks;
    use IsDeselectable;
    use IsInline;

    public function setUp(): void
    {
        $this->setType('bulk');
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
