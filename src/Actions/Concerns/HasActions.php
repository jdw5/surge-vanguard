<?php

namespace Conquest\Table\Actions\Concerns;

use Conquest\Table\Actions\BaseAction;
use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Actions\InlineAction;
use Conquest\Table\Actions\PageAction;
use Conquest\Table\DataObjects\ActionData;
use Conquest\Table\DataObjects\ActionTypeData;
use Conquest\Table\DataObjects\BulkActionData;
use Conquest\Table\DataObjects\InlineActionData;
use Conquest\Table\Exceptions\InvalidActionException;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

trait HasActions
{
    public const INLINE_ACTION = 'action:inline';

    public const BULK_ACTION = 'action:bulk';

    public const PAGE_ACTION = 'action:page';

    public const EXPORT_ACTION = 'action:export';

    private Collection $cachedActions;

    protected $actions;

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
        return ! is_null($this->getActionRoute());
    }

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

    public function setActions(?array $actions): void
    {
        if (is_null($actions)) {
            return;
        }
        $this->actions = $actions;
    }

    public function getTableActions(): Collection
    {
        return $this->cachedActions ??= collect($this->getActions())
            ->filter(static fn (BaseAction $action): bool => $action->authorized());
    }

    public function getInlineActions(): Collection
    {
        return $this->getTableActions()
            ->filter(static fn (BaseAction $action): bool => $action instanceof InlineAction);
    }

    public function getBulkActions(): Collection
    {
        return $this->getTableActions()
            ->filter(static fn (BaseAction $action): bool => $action instanceof BulkAction);
    }

    public function getPageActions(): Collection
    {
        return $this->getTableActions()
            ->filter(static fn (BaseAction $action): bool => $action instanceof PageAction);
    }

    public function getDefaultAction(): ?BaseAction
    {
        return $this->getInlineActions()
            ->first(fn (InlineAction $action): bool => $action->isDefault());
    }

    // Need to be able to run actions against records
    public function applyRecordToActions(Model $record): Collection
    {
        return $this->getTableActions();
        // ->map(fn (BaseAction $action): BaseAction => $action->applyRecord($record));
    }

    // Handler

    protected static function redirectOrExit(HttpFoundationResponse $response): void
    {
        $response->send();
        exit;
    }

    public function handle(Request $request)
    {
        $type = match (ActionTypeData::from($request)->getType()) {
            static::INLINE_ACTION => InlineActionData::from($request),
            static::BULK_ACTION => BulkActionData::from($request),
            default => static::redirectOrExit(back())
        };

        try {
            $action = $this->resolveAction($type);
        } catch (InvalidActionException $e) {
            return;
        }

        match (get_class($action)) {
            InlineAction::class => $this->executeInlineAction($action, $type),
            BulkAction::class => $this->executeBulkAction($action, $type),
            default => static::redirectOrExit(back())
        };
    }

    private function resolveAction(ActionData $data): BaseAction
    {
        $actions = match (get_class($data)) {
            InlineActionData::class => $this->getInlineActions(),
            BulkActionData::class => $this->getBulkActions(),
            default => throw new Exception('Invalid action data')
        };

        if (! $action = $actions->first(fn (BaseAction $action) => $action->getName() === $data->getName())) {
            throw new InvalidActionException($data->getName());
        }

        return $action;
    }

    private function executeInlineAction(InlineAction $action, InlineActionData $data): void
    {
        $modelClass = $this->getModelClass();
        $record = $this->resolveModel($modelClass, $data->getId());

        $action->applyAction($modelClass, $record);
    }

    private function executeBulkAction(BulkAction $action, BulkActionData $data): void
    {
        $query = $this->getResource();
        $modelClass = $this->getModelClass();
        $key = $this->getTableKey();

        $query = match (true) {
            $data->isAll() => $query->whereNotIn($key, $data->getExcept()),
            default => $query->whereIn($key, $data->getOnly())
        };

        $query->{$action->getChunkMethod()}($action->getChunkSize(),
            fn (Collection $records) => $records->each(
                fn (Model $record) => $action->applyAction($modelClass, $record)
            )
        );
    }
}
