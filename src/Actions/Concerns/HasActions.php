<?php

namespace Conquest\Table\Actions\Concerns;

use Exception;
use Conquest\Table\Actions\Action;
use Conquest\Table\Actions\Export;
use Conquest\Table\Actions\BaseAction;
use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Actions\PageAction;
use Illuminate\Database\Eloquent\Model;
use Conquest\Table\Actions\InlineAction;
use Conquest\Table\DataObjects\ActionTypeData;
use Conquest\Table\DataObjects\InlineActionData;
use Conquest\Table\Actions\DataTransferObjects\BulkActionData;

/**
 * Define a class as having actions.
 */
trait HasActions
{
    public const INLINE_ACTION = 'action:inline';
    public const BULK_ACTION = 'action:bulk';
    public const EXPORT_ACTION = 'action:export';

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

    // Handling



    public static function handle(Request $request)
    {
        // Get the DTO for a specific object
        $type = match (ActionTypeData::from($request)->getType()) {
            static::INLINE_ACTION => InlineActionData::from($request),
            static::BULK_ACTION => BulkActionData::from($request),
            // static::EXPORT_ACTION => ExportActionData::from($request);
            default => back()
        };
    
        if ($type instanceof Response) {
            $type->send();
            exit;
        }

        try {
            $action = static::resolve($type);
        } catch (InvalidActionException $e) {
            return back()->withErrors($exception->getMessage());
        }

        // Ensure that the action is authorized
        if (!$action->authorized()) {
            abort(403);
        }

        match ($data::class) {
            InlineActionData::class => static::executeInlineAction($data),
            BulkActionData::class => static::executeBulkAction($data),
            // ExportActionData::class => static::executeExportAction($data),
            default => back()
        };
    }

    private function resolveAction(ActionData $data): BaseAction
    {
        $actions = match ($data::class) {
            InlineActionData::class => $this->getInlineActions(showHidden: true),
            BulkActionData::class => $this->getBulkActions(showHidden: true),
            // ExportActionData::class => $this->getExportActions(showHidden: true),
            default => collect([])
        };

        if (!$action = $actions->first(fn (BaseAction $action) => $action->getName() === $data->getName())) {
            throw new InvalidActionException($data->getName());
        }

        return $action;
    }

    private function executeInlineAction(InlineAction $data): mixed
    {
        /**
         * @var Table $table
         * @var InlineAction $action
         */
        [$table, $action] = $this->resolveAction($data);

        $modelClass = $table->getModelClass();
        $record = $action->resolveModel($modelClass, $data);
        $result = $table->evaluate(
            value: $action->getAction(),
            named: [
                'record' => $record,
            ],
            typed: [
                Model::class => $record,
                $this->getModelClass() => $record,
            ],
        );

        if ($result instanceof Response) {
            $result->send();
            exit;
        }

        return back();
    }

    private function executeBulkAction(BulkAction $data): mixed
    {
        $model = $this->getModelClass();
        $key = $this->getTableKey();

        /** @var \Illuminate\Database\Eloquent\Builder */
        $query = $table->getRefinedQuery();
        $query = match (true) {
            $data->all === true => $query->whereNotIn($key, $data->except),
            default => $query->whereIn($key, $data->only)
        };

        // If the action has a 'query' parameter, we pass it.
        // Otherwise we execute the query here and pass the result as 'records'.
        $reflection = new \ReflectionFunction($action->getAction());
        $hasRecordsParameter = collect($reflection->getParameters())
            ->some(fn (\ReflectionParameter $parameter) => 'records' === $parameter->getName() || Collection::class === $parameter->getType());

        $result = $table->evaluate(
            value: $action->getAction(),
            named: [
                'query' => $query,
                ...($hasRecordsParameter ? ['records' => $query->get()] : []),
            ],
            typed: [
                Builder::class => $query,
                ...($hasRecordsParameter ? [Collection::class => $query->get()] : []),
            ],
        );

        if ($result instanceof Response) {
            $result->send();
            exit;
        }

        return back();
    }
}
