<?php

namespace Conquest\Table\Actions\Concerns;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Conquest\Table\Actions\Action;
use Conquest\Table\Actions\Export;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Conquest\Table\Actions\BaseAction;
use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Actions\PageAction;
use Illuminate\Database\Eloquent\Model;
use Conquest\Table\Actions\InlineAction;
use Conquest\Table\DataObjects\ActionData;
use Conquest\Table\DataObjects\ActionTypeData;
use Conquest\Table\DataObjects\BulkActionData;
use Conquest\Table\DataObjects\InlineActionData;
use Conquest\Table\Actions\Exceptions\InvalidActionException;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

/**
 * Define a class as having actions.
 */
trait HasActions
{
    public const INLINE_ACTION = 'action:inline';
    public const BULK_ACTION = 'action:bulk';
    public const EXPORT_ACTION = 'action:export';

    private Collection $cachedActions;
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
        if (is_null($actions)) {
            return;
        }
        $this->actions = $actions;
    }

    /**
     * Retrieve the actions for the class.
     * 
     * @return Collection
     */
    public function getTableActions(): Collection
    {
        return $this->cachedActions ??= collect($this->getActions())
            ->filter(static fn (BaseAction $action): bool => $action->authorized());
    }

    /**
     * Retrieve the inline actions for the class.
     * 
     * @return Collection
     */
    public function getInlineActions(): Collection
    {
        return $this->getTableActions()
            ->filter(static fn (BaseAction $action): bool => $action instanceof InlineAction);
    }

    /**
     * Retrieve the bulk actions for the class.
     * 
     * @return Collection
     */
    public function getBulkActions(): Collection
    {
        return $this->getTableActions()
            ->filter(static fn (BaseAction $action): bool => $action instanceof BulkAction);
    }

    /**
     * Retrieve the page actions for the class.
     * 
     * @return Collection
     */
    public function getPageActions(): Collection
    {
        return $this->getTableActions()
            ->filter(static fn (BaseAction $action): bool => $action instanceof PageAction);
    }

    /**
     * Retrieve the default action for the class.
     *
     * @return BaseAction if a default is defined
     * @return null if no default is defined
     */
    public function getDefaultAction(): ?BaseAction
    {
        return $this->getInlineActions()
            ->first(fn (BaseAction $action): bool => $action->isDefault());
    }


    // Handling
    public static function redirectOrExit(HttpFoundationResponse $response): void
    {
        $response->send();
        exit;
    }



    public static function handle(Request $request)
    {
        // Get the DTO for a specific object
        $type = match (ActionTypeData::from($request)->getType()) {
            static::INLINE_ACTION => InlineActionData::from($request),
            static::BULK_ACTION => BulkActionData::from($request),
            // static::EXPORT_ACTION => ExportActionData::from($request);
            default => static::redirectOrExit(back())
        };
    

        try {
            $action = static::resolve($type);
        } catch (InvalidActionException $e) {
            static::redirectOrExit(back());
        }

        match ($action::class) {
            InlineAction::class => $this->executeInlineAction($action),
            BulkAction::class => $this->executeBulkAction($action),
            // ExportActionData::class => static::executeExportAction($data),
            default => static::redirectOrExit(back())
        };
    }

    private function resolveAction(ActionData $data): BaseAction
    {
        $actions = match ($data::class) {
            InlineActionData::class => $this->getInlineActions(),
            BulkActionData::class => $this->getBulkActions(),
            // ExportActionData::class => $this->getExportActions(showHidden: true),
            default => throw new Exception('Invalid action data')
        };

        if (!$action = $actions->first(fn (BaseAction $action) => $action->getName() === $data->getName())) {
            throw new InvalidActionException($data->getName());
        }

        return $action;
    }

    private function executeInlineAction(InlineAction $action): mixed
    {
        $modelClass = $this->getModelClass();
        $record = $this->resolveModel($modelClass, $action);
        
        $this->evaluate(
            value: $this->getAction(),
            named: [
                'record' => $record,
            ],
            typed: [
                Model::class => $record,
                $this->getModelClass() => $record,
            ],
        );

        $this->redirectOrExit(back());
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

        $this->redirectOrExit($result || back());
        
    }
}
