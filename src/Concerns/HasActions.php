<?php

namespace Conquest\Table\Concerns;

use Conquest\Table\Actions\BaseAction;
use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Actions\InlineAction;
use Conquest\Table\Actions\PageAction;
use Illuminate\Support\Collection;

trait HasActions
{
    /**
     * @var Collection<int, Conquest\Table\Actions\BaseAction>
     */
    private Collection $cachedActions;

    /**
     * @var array<int, Conquest\Table\Actions\BaseAction>
     */
    protected $actions;

    /**
     * Get the actions for the table.
     *
     * @internal
     *
     * @return array<int, Conquest\Table\Actions\BaseAction>
     */
    public function getActions(): array
    {
        if (isset($this->actions)) {
            return $this->actions;
        }

        if (method_exists($this, 'actions')) {
            return $this->actions();
        }

        return [];
    }

    /**
     * Set the actions for the table.
     *
     * @param  array<int, Conquest\Table\Actions\BaseAction>  $actions
     */
    public function setActions(?array $actions): void
    {
        if (is_null($actions)) {
            return;
        }

        $this->actions = $actions;
    }

    /**
     * Get all available actions.
     *
     * @return Collection<int, Conquest\Table\Actions\BaseAction>
     */
    public function getTableActions(): Collection
    {
        return $this->cachedActions ??= collect($this->getActions())
            ->filter(static fn (BaseAction $action): bool => $action->isAuthorized());
    }

    /**
     * Get the inline actions.
     *
     * @return Collection<int, Conquest\Table\Actions\InlineAction>
     */
    public function getInlineActions(): Collection
    {
        return $this->getTableActions()
            ->filter(static fn (BaseAction $action): bool => $action instanceof InlineAction);
    }

    /**
     * Get the bulk actions.
     *
     * @return Collection<int, Conquest\Table\Actions\BulkAction>
     */
    public function getBulkActions(): Collection
    {
        return $this->getTableActions()
            ->filter(static fn (BaseAction $action): bool => $action instanceof BulkAction);
    }

    /**
     * Get the page actions.
     *
     * @return Collection<int, Conquest\Table\Actions\PageAction>
     */
    public function getPageActions(): Collection
    {
        return $this->getTableActions()
            ->filter(static fn (BaseAction $action): bool => $action instanceof PageAction);
    }

    /**
     * Get the default inline action for a record.
     *
     * @return ?Conquest\Table\Actions\BaseAction
     */
    public function getDefaultAction(): ?BaseAction
    {
        return $this->getInlineActions()
            ->first(fn (InlineAction $action): bool => $action->isDefault());
    }
}
