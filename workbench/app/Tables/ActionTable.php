<?php

namespace Workbench\App\Tables;

use Carbon\Carbon;
use Conquest\Table\Table;
use Conquest\Table\Record\Record;
use Workbench\App\Enums\TestRole;
use Workbench\App\Models\TestUser;
use Conquest\Table\Sorts\Sort;
use Conquest\Table\Columns\Column;
use Illuminate\Database\Eloquent\Builder;
use Conquest\Table\Filters\Filter;
use Conquest\Table\Options\Option;
use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Actions\PageAction;
use Conquest\Table\Actions\InlineAction;
use Conquest\Table\Filters\QueryFilter;
use Conquest\Table\Filters\SelectFilter;

class ActionTable extends Table
{
    protected function defineColumns(): array
    {
        return [
            Column::make('id')->asKey()->hide(),
            Column::make('name')->sort()->transform(fn ($value) => strtoupper($value)),
            Column::make('email')->fallback('No email')->sort(),
            Column::make('created_at')->transform(fn (?Carbon $value) => $value?->format('d/m/Y')),
            Column::make('role')->label('User Role')->fallback('No role')
        ];
    }

    protected function defineRefinements(): array
    {
        return [
        ];
    }

    protected function defineActions(): array
    {
        return [
            PageAction::make('add')->label('Add User'),

            // BulkAction::make('delete')->label('Delete Users')->endpoint('delete', ),

            InlineAction::make('view')->label('View User')->default()->whenRecord(fn (Record $record) => $record->id > 1),
            InlineAction::make('edit')->label('Edit User')->default()->whenRecord('name', '!=', 'xxxxx'),
            InlineAction::make('delete')->label('Delete User')

        ];
    }
}