<?php

namespace Conques\Table\Actions\Http;

use Conquest\Table\Actions\BaseAction;
use Conquest\Table\Actions\Enums\Context;
use Conquest\Table\Actions\Http\DTOs\ActionData;
use Conquest\Table\Actions\Http\DTOs\BulkActionData;
use Conquest\Table\Actions\Http\DTOs\InlineActionData;
use Conquest\Table\Table;
use Illuminate\Http\Request;

class ActionHandler
{
    public function __invoke(Request $request)
    {
        $context = Context::tryFrom($request->input('type'));

        match ($context) {
            Context::Inline => $this->handleInlineAction(InlineActionData::from($request)),
            Context::Bulk => $this->handleBulkAction(BulkActionData::from($request)),
            default => throw new \Exception('Invalid action type'),
        };

        // $data = ActionData::from($request);

        // Select the type and create DTO based on type
        // An invalid type should throw exception which is rendered as a 400

        // Resolve the table and action if not already resolved from route binding

        // Apply the action
    }

    protected function handleInlineAction(InlineActionData $data)
    {
        // Resolve the table

    }

    protected function handleBulkAction(BulkActionData $data) {}

    protected function resolveTable(ActionData $data): Table
    {
        $decoded = Table::decode($data->table);

        try {
            $table = resolve($decoded);
        } catch (\Throwable) {
            throw CouldNotResolveTableException::with($decoded);
        }

        if (! $table instanceof Table) {
            throw InvalidTableException::with($decoded);
        }

        if ($table->isAnonymous()) {
            throw InvalidTableException::cannotBeAnonymous();
        }

        return $table;
    }

    protected function resolveAction(ActionData $data, Table $table): BaseAction {}

    // public function handle(Request $request)
    // {
    //     $type = match (ActionTypeData::from($request)->getType()) {
    //         static::INLINE_ACTION => InlineActionData::from($request),
    //         static::BULK_ACTION => BulkActionData::from($request),
    //         default => static::redirectOrExit(back())
    //     };

    //     try {
    //         $action = $this->resolveAction($type);
    //     } catch (InvalidActionException $e) {
    //         return;
    //     }

    //     match (get_class($action)) {
    //         InlineAction::class => $this->executeInlineAction($action, $type),
    //         BulkAction::class => $this->executeBulkAction($action, $type),
    //         default => static::redirectOrExit(back())
    //     };
    // }

    // private function resolveAction(ActionData $data): BaseAction
    // {
    //     $actions = match (get_class($data)) {
    //         InlineActionData::class => $this->getInlineActions(),
    //         BulkActionData::class => $this->getBulkActions(),
    //         default => throw new Exception('Invalid action data')
    //     };

    //     if (! $action = $actions->first(fn (BaseAction $action) => $action->getName() === $data->getName())) {
    //         throw new InvalidActionException($data->getName());
    //     }

    //     return $action;
    // }

    // private function executeInlineAction(InlineAction $action, InlineActionData $data): void
    // {
    //     $modelClass = $this->getModelClass();
    //     $record = $this->resolveModel($modelClass, $data->getId());

    //     $action->applyAction($modelClass, $record);
    // }

    // private function executeBulkAction(BulkAction $action, BulkActionData $data): void
    // {
    //     $query = $this->getResource();
    //     $modelClass = $this->getModelClass();
    //     $key = $this->getTableKey();

    //     $query = match (true) {
    //         $data->isAll() => $query->whereNotIn($key, $data->getExcept()),
    //         default => $query->whereIn($key, $data->getOnly())
    //     };

    //     $query->{$action->getChunkMethod()}($action->getChunkSize(),
    //         fn (Collection $records) => $records->each(
    //             fn (Model $record) => $action->applyAction($modelClass, $record)
    //         )
    //     );
    // }
}
