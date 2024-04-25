<?php

namespace Jdw5\Vanguard\Concerns;

use Jdw5\Vanguard\Table\Actions\BaseAction;
use Jdw5\Vanguard\Table\Actions\BulkAction;
use Jdw5\Vanguard\Table\Actions\PageAction;
use Jdw5\Vanguard\Table\Actions\InlineAction;
use Illuminate\Support\Collection;

trait HasActions
{
    protected mixed $actions = null;

    /**
     * Define the actions for the table.
     * 
     * @return array
     */
    protected function defineActions(): array
    {
        return [];
    }

    public function getActions(): Collection
    {
        return $this->actions ??= collect($this->defineActions())
            ->filter(static fn (BaseAction $action): bool => !$action->isExcluded());
    }

    public function getInlineActions(): Collection
    {
        return $this->getActions()->filter(static fn (BaseAction $action): bool => $action instanceof InlineAction)->values();
    }

    public function getBulkActions(): Collection
    {
        return $this->getActions()->filter(static fn (BaseAction $action): bool => $action instanceof BulkAction)->values();
    }

    public function getPageActions(): Collection
    {
        return $this->getActions()->filter(static fn (BaseAction $action): bool => $action instanceof PageAction)->values();
    }

    public function getDefaultAction(): ?BaseAction
    {
        return $this->getActions()->first(static fn (BaseAction $action): bool => $action instanceof InlineAction && $action->isDefault());
    }
}
