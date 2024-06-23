<?php

namespace Jdw5\Vanguard\Actions\Concerns;

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
    private Collection $cachedActions;

    protected array $actions;

    protected function setActions(array|null $actions = null): void
    {
        if (is_null($actions)) return;
        $this->actions = $actions;
    }

    /**
     * Define the actions for the class.
     * 
     * @return array
     */
    protected function getRawActions(): array
    {
        if (isset($this->actions)) {
            return $this->actions;
        }

        if (function_exists('actions')) {
            return $this->actions();
        }

        return [];
    }

    /**
     * Retrieve the actions for the class.
     * 
     * @return Collection
     */
    public function getActions(): Collection
    {
        return $this->cachedActions ??= collect($this->getRawActions())
            ->filter(static fn (BaseAction $action): bool => !$action->authorized());
    }

    /**
     * Retrieve the inline actions for the class.
     * 
     * @return Collection
     */
    public function getRowActions(): Collection
    {
        return $this->getActions()
            ->filter(static fn (BaseAction $action): bool => $action instanceof InlineAction)->values();
    }

    /**
     * Retrieve the bulk actions for the class.
     * 
     * @return Collection
     */
    public function getBulkActions(): Collection
    {
        return $this->getActions()
            ->filter(static fn (BaseAction $action): bool => $action instanceof BulkAction)->values();
    }

    /**
     * Retrieve the page actions for the class.
     * 
     * @return Collection
     */
    public function getPageActions(): Collection
    {
        return $this->getActions()
            ->filter(static fn (BaseAction $action): bool => $action instanceof PageAction)->values();
    }

    /**
     * Retrieve the default action for the class.
     * 
     * @return BaseAction if a default is defined
     * @return null if no default is defined
     */
    public function getDefaultAction(): ?BaseAction
    {
        return $this->getActions()
            ->first(static fn (BaseAction $action): bool => $action instanceof InlineAction && $action->isDefault());
    }
}
