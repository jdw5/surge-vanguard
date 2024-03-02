<?php

namespace Jdw5\SurgeVanguard\Testing;

use Carbon\Carbon;
use App\Models\User;
use Jdw5\SurgeVanguard\Table\Table;
use Jdw5\SurgeVanguard\Refining\Sorts\Sort;
use Jdw5\SurgeVanguard\Table\Columns\Column;
use Jdw5\SurgeVanguard\Refining\Filters\Filter;
use Jdw5\SurgeVanguard\Table\Actions\BulkAction;
use Jdw5\SurgeVanguard\Table\Actions\PageAction;
use Jdw5\SurgeVanguard\Table\Actions\InlineAction;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\SurgeVanguard\Table\Pagination\PaginateType;

class UsersTable extends Table
{
    // protected string $model = User::class;
    // protected $key = 'id';

    public function defineQuery(): Builder
    {
        return User::where('id', '<', 50);
    }

    protected function defineActions(): array
    {
        return [
            PageAction::make('create')->label('Create User')->endpoint(route('landing.index'))->get(),
            PageAction::make('edit')->label('Edit Users'),
            BulkAction::make('delete')->label('Delete'),
            InlineAction::make('delete'),
        ];
    }
    
    protected function defineColumns(): array
    {
        return [
            Column::make('id')->label('#')->sort('test')->asKey(),
            Column::make('name')->label('Username'),
            Column::make('email'),
            Column::make('created_at')->label('Created')->transform(fn (Carbon $value) => $value->format('d M Y')),
        ];
    }

    protected function defineRefinements(): array
    {
        return [
            Filter::make('email')->loose()->default('a'),
            Filter::make('id', 'user')->label('User ID')->exact(),

            Sort::make('name', 'full_name'),
            Sort::make('name', 'full_name')->desc(),
            Sort::make('email'),
            Sort::make('created_at')
        ];
    }
}