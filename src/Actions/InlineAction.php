<?php

namespace Conquest\Table\Actions;

use Conquest\Core\Concerns\HasHttpMethod;
use Conquest\Core\Concerns\HasRoute;
use Conquest\Core\Concerns\IsDefault;
use Conquest\Table\Actions\Concerns\Confirmation\Confirms;
use Conquest\Table\Actions\Concerns\HasAction;
use Conquest\Table\Actions\Concerns\IsBulk;
use Conquest\Table\Table;

class InlineAction extends BaseAction
{
    use Confirms;
    use HasAction;
    use HasHttpMethod;
    use HasRoute;
    use IsDefault;
    use IsBulk;

    public function setUp(): void
    {
        $this->setType(Table::INLINE_ACTION);
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            $this->toArrayConfirm(),
            [
                'route' => $this->getResolvedRoute(),
                'method' => $this->getMethod(),
                'actionable' => $this->hasAction(),
            ]
        );
    }

    // public function forRecord($record): static
    // {
    //     if ($this->hasRoute()) $this->resolveRoute($record);
    //     return $this;
    // }
}
