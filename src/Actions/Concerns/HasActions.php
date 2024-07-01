<?php

namespace Conquest\Table\Actions\Concerns;

use Conquest\Table\Actions\BaseAction;
use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Actions\PageAction;
use Conquest\Table\Actions\RowAction;

/**
 * Define a class as having actions.
 */
trait HasActions
{
    private array $cachedActions;

    private array $cachedRowActions;

    protected array $actions = [];

    /**
     * Define the actions for the class.
     */
    protected function getActions(): array
    {
        if (isset($this->actions)) {
            return $this->actions;
        }

        if (method_exists($this, 'actions')) {
            return $this->actions();
        }

        return [];
    }

    protected function setActions(?array $actions): void
    {
        if (is_null($actions)) {
            return;
        }
        $this->actions = $actions;
    }

    /**
     * Retrieve the actions for the class.
     */
    public function getTableActions(): array
    {

        return $this->cachedActions ??= array_filter($this->getActions(), static fn (BaseAction $action): bool => $action->authorized());
    }

    /**
     * Retrieve the inline actions for the class.
     */
    public function getRowActions(): array
    {
        return $this->cachedRowActions ??= array_values(array_filter($this->getTableActions(), static fn (BaseAction $action): bool => $action instanceof RowAction));
    }

    /**
     * Retrieve the bulk actions for the class.
     */
    public function getBulkActions(): array
    {
        return array_values(array_filter($this->getTableActions(), static fn (BaseAction $action): bool => $action instanceof BulkAction));
    }

    /**
     * Retrieve the page actions for the class.
     */
    public function getPageActions(): array
    {
        return array_values(array_filter($this->getTableActions(), static fn (BaseAction $action): bool => $action instanceof PageAction));
    }

    /**
     * Retrieve the default action for the class.
     *
     * @return BaseAction if a default is defined
     * @return null if no default is defined
     */
    public function getDefaultAction(): ?BaseAction
    {
        return collect($this->getRowActions())
            ->first(static fn (BaseAction $action): bool => $action->isDefault());
    }

    public function addAction(BaseAction $action): static
    {
        $this->actions[] = $action;

        return $this;
    }
}
