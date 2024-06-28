<?php

namespace Workbench\App\Tables;

use Carbon\Carbon;
use Jdw5\Vanguard\Table\Table;
use Jdw5\Vanguard\Table\Record\Record;
use Workbench\App\Enums\TestRole;
use Workbench\App\Models\TestUser;
use Jdw5\Vanguard\Sorts\Sort;
use Jdw5\Vanguard\Table\Columns\Column;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Filters\Filter;
use Jdw5\Vanguard\Options\Option;
use Jdw5\Vanguard\Table\Actions\BulkAction;
use Jdw5\Vanguard\Table\Actions\PageAction;
use Jdw5\Vanguard\Table\Actions\InlineAction;
use Jdw5\Vanguard\Filters\QueryFilter;
use Jdw5\Vanguard\Filters\SelectFilter;

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