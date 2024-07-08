<?php

namespace Conquest\Table\Actions\Concerns;

use Conquest\Table\Actions\Action;
use Conquest\Table\Actions\BaseAction;
use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Actions\PageAction;
use Conquest\Table\Actions\InlineAction;

/**
 * Define a class as having actions.
 */
trait HasActions
{
    private array $cachedActions;
    protected $actions; // array
    protected $actionRoute;

    public function getActionRoute(): ?string
    {
        if (isset($this->actionRoute)) {
            return $this->actionRoute;
        }

        if (method_exists($this, 'actionRoute')) {
            return $this->actionRoute();
        }

        return null;
    }

    public function hasActionRoute(): bool
    {
        return !is_null($this->getActionRoute());
    }

    /**
     * Define the actions for the class.
     * 
     * @return array
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

    public function setActions(array|null $actions): void
    {
        if (is_null($actions)) return;
        $this->actions = $actions;
    }

    /**
     * Retrieve the actions for the class.
     * 
     * @return array
     */
    public function getTableActions(): array
    {
        return $this->cachedActions ??= array_filter(
            $this->getActions(), static fn (BaseAction $action): bool => $action->authorized()
        );
    }

    /**
     * Retrieve the inline actions for the class.
     * 
     * @return array
     */
    public function getInlineActions(): array
    {
        return $this->cachedActions ??= array_values(
            array_filter($this->getTableActions(), static fn (BaseAction $action): bool => $action instanceof InlineAction)
        );
    }

    /**
     * Retrieve the bulk actions for the class.
     * 
     * @return array
     */
    public function getBulkActions(): array
    {
        return array_values(
            array_filter($this->getTableActions(), static fn (BaseAction $action): bool => $action instanceof BulkAction)
        );
    }

    /**
     * Retrieve the page actions for the class.
     * 
     * @return array
     */
    public function getPageActions(): array
    {
        return array_values(
            array_filter($this->getTableActions(), static fn (BaseAction $action): bool => $action instanceof PageAction)
        );
    }

    /**
     * Retrieve the default action for the class.
     * 
     * @return BaseAction if a default is defined
     * @return null if no default is defined
     */
    public function getDefaultAction(): ?BaseAction
    {
        foreach ($this->getRowActions() as $action) {
            if ($action->isDefault()) {
                return $action;
            }
        }
        return null;
    }
}
