<?php

namespace Jdw5\Vanguard\Concerns;

use Jdw5\Vanguard\Table\Actions\BaseAction;
use Jdw5\Vanguard\Table\Actions\BulkAction;
use Jdw5\Vanguard\Table\Actions\PageAction;
use Jdw5\Vanguard\Table\Actions\InlineAction;
use Illuminate\Support\Collection;

trait HasActions
{
    protected mixed $cachedActions = null;

    public function getActions(): Collection
    {
        return $this->cachedActions ??= collect($this->defineActions())
            ->filter(static fn (BaseAction $action): bool => !$action->isExcluded());
    }

    public function getInlineActions(bool $showHidden = false): Collection
    {
        return $this->getActions($showHidden)->filter(static fn (BaseAction $action): bool => $action instanceof InlineAction);
    }

    public function getBulkActions(bool $showHidden = false): Collection
    {
        return $this->getActions($showHidden)->filter(static fn (BaseAction $action): bool => $action instanceof BulkAction);
    }

    public function getPageActions(bool $showHidden = false): Collection
    {
        return $this->getActions($showHidden)->filter(static fn (BaseAction $action): bool => $action instanceof PageAction);
    }

    public function getDefaultAction(): ?BaseAction
    {
        return $this->getActions()->first(static fn (BaseAction $action): bool => $action instanceof InlineAction && $action->isDefault());
    }

    protected function defineActions(): array
    {
        return [];
    }
}
