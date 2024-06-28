<?php

namespace Jdw5\Vanguard\Actions\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Jdw5\Vanguard\Actions\RowAction;
use Jdw5\Vanguard\Actions\BaseAction;
use Jdw5\Vanguard\Actions\BulkAction;
use Jdw5\Vanguard\Actions\PageAction;

/**
 * Define a class as having actions.
 */
trait HasActions
{
    private Collection $cachedActions;

    protected array $actions;

    protected function setActions(array|null $actions): void
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

        if (method_exists($this, 'actions')) {
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
            ->filter(static fn (BaseAction $action): bool => $action->authorized());
    }

    /**
     * Retrieve the inline actions for the class.
     * 
     * @return Collection
     */
    public function getRowActions(): Collection
    {
        return $this->getActions()
            ->filter(static fn (BaseAction $action): bool => $action instanceof RowAction)->values();
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
            ->first(static fn (BaseAction $action): bool => $action instanceof RowAction && $action->isDefault());
    }

    public function addAction(BaseAction $action): static
    {
        $this->actions[] = $action;
        return $this;
    }

    
    public static function handle(Request $request): mixed
    {
        [$type, $name] = explode(':', $request->input('name'));
        // If either doesn't exist, then the request is invalid
        if (!$type || !$name) abort(400);

        return match ($type) {
            'action' => static::handleAction($request, $name),
            'export' => static::handleExport($request, $name),
            default => null
        };
        /**
         * return Table::handle($request); 
         * Accepts a request, pulls out the values
         * Find the first action OR export which matches the `type:name` and `httpMethod`
         * 
         * If anything is found, check the permissions -> authorize
         * If authorize fails, abort(403)
         * 
         * Export:
         * create the export for the table
         * -> frontend handles it as axios NOT inertia
         * 
         * Actions format depends on whether it's bulk or row
         */
    }

    private static function handleAction(Request $request, string $name): mixed
    {
        // Find the action which has the name and method the same as the request
        $action = static::findAction($name, $request->method(), $request->get('type'));

        if (!$action) return;

        return $action->handle($request);
    }

    private static function findAction(string $name, string $method, string $type = null): BaseAction|null
    {
        if (is_null($type)) $type = 'row';

        return static::getActions()->first(fn($action) => $action->getName() === $name 
            && $action->getMethod() === $method 
            && $action->getType() === $type
        );
    }

    private static function handleExport(Request $request, string $name): mixed
    {
        $export = static::findExport($name, $request->method());

        if (!$export) return;
        $export->handle($request);
        return $export->after();
    }
}
