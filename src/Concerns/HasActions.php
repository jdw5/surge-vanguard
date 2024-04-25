<?php

namespace Jdw5\Vanguard\Concerns;

use Jdw5\Vanguard\Table\Actions\BaseAction;
use Jdw5\Vanguard\Table\Actions\BulkAction;
use Jdw5\Vanguard\Table\Actions\PageAction;
use Jdw5\Vanguard\Table\Actions\InlineAction;
use Illuminate\Support\Collection;

/**
 * Define a class as having actions.
 */
trait HasActions
{
    protected mixed $actions = null;

    /**
     * Define the actions for the class.
     * 
     * @return array
     */
    protected function defineActions(): array
    {
        return [];
    }

    /**
     * Retrieve the actions for the class.
     * 
     * @return Collection
     */
    public function getActions(): Collection
    {
        return $this->actions ??= collect($this->defineActions())
            ->filter(static fn (BaseAction $action): bool => !$action->isExcluded());
    }

    /**
     * Retrieve the inline actions for the class.
     * 
     * @return Collection
     */
    public function getInlineActions(): Collection
    {
        return $this->getActions()->filter(static fn (BaseAction $action): bool => $action instanceof InlineAction)->values();
    }

    /**
     * Retrieve the bulk actions for the class.
     * 
     * @return Collection
     */
    public function getBulkActions(): Collection
    {
        return $this->getActions()->filter(static fn (BaseAction $action): bool => $action instanceof BulkAction)->values();
    }

    /**
     * Retrieve the page actions for the class.
     * 
     * @return Collection
     */
    public function getPageActions(): Collection
    {
        return $this->getActions()->filter(static fn (BaseAction $action): bool => $action instanceof PageAction)->values();
    }

    /**
     * Retrieve the default action for the class.
     * 
     * @return BaseAction if a default is defined
     * @return null if no default is defined
     */
    public function getDefaultAction(): ?BaseAction
    {
        return $this->getActions()->first(static fn (BaseAction $action): bool => $action instanceof InlineAction && $action->isDefault());
    }
}
