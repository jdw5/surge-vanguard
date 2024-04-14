<?php

namespace Workbench\App\Tables;

use Jdw5\Vanguard\Table\Table;
use Workbench\App\Enums\TestRole;
use Workbench\App\Models\TestUser;
use Jdw5\Vanguard\Refining\Sorts\Sort;
use Jdw5\Vanguard\Table\Columns\Column;
use Illuminate\Database\Eloquent\Builder;
use Jdw5\Vanguard\Refining\Filters\Filter;
use Jdw5\Vanguard\Refining\Options\Option;
use Jdw5\Vanguard\Table\Actions\BulkAction;
use Jdw5\Vanguard\Table\Actions\PageAction;
use Jdw5\Vanguard\Table\Actions\InlineAction;
use Jdw5\Vanguard\Refining\Filters\QueryFilter;
use Jdw5\Vanguard\Refining\Filters\SelectFilter;

class BasicTable extends Table
{
    // protected function definePagination()
    // {
    //     return 10;
    // }

    protected $model = TestUser::class;

    protected function defineColumns(): array
    {
        return [
            Column::make('id')->asKey()->hide(),
            Column::make('name')->sort(),
            Column::make('email')->fallback('No email')->sort(),
            Column::make('created_at')->transform(fn ($value) => $value->format('d/m/Y H:i:s')),
            Column::make('role')->label('User Role')
        ];
    }

    protected function defineRefinements(): array
    {
        return [
            Filter::make('name')->loose(),
            SelectFilter::make('role')->options(Option::enum(TestRole::class, 'label')),
            // QueryFilter::make('id')->query(fn (Builder $builder, $value) => $builder->where('id', '>', $value))
           
            Sort::make('created_at', 'newest')->desc()->default(),
            Sort::make('created_at', 'oldest')->asc(),   
            
        ];
    }

    protected function defineActions(): array
    {
        return [
            PageAction::make('add')->label('Add User'),

            BulkAction::make('delete')->label('Delete Users'),

            InlineAction::make('view')->label('View User')->default(),
            InlineAction::make('edit')->label('Edit User'),
            InlineAction::make('delete')->label('Delete User')

        ];
    }
}