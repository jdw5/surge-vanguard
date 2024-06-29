<?php

namespace Workbench\App\Tables;

use Carbon\Carbon;
use Conquest\Table\Table;
use Workbench\App\Models\TestUser;
use Conquest\Table\Sorts\Sort;
use Conquest\Table\Columns\Column;
use Conquest\Table\Filters\Filter;
use Conquest\Table\Options\Option;
use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Actions\PageAction;
use Conquest\Table\Actions\InlineAction;
use Conquest\Table\Filters\QueryFilter;
use Conquest\Table\Filters\SelectFilter;
use Conquest\Table\Actions\BaseAction;
use Workbench\App\Models\Product;

final class ProductTable extends Table
{
    protected $resource = Product::class;
    protected $search = 'name';
    protected $pagination = [10, 50, 100];

    protected function columns(): array
    {
        return [
            Column::make('public_id')->asKey()->hide(),
            Column::make('name')->sort(),
            Column::make('description')->fallback('No description')->sort(),
            Column::make('created_at')->transform(fn (Carbon $value) => $value->format('d/m/Y H:i:s')),
            Column::make('price')->transform(fn ($value) => '$' . number_format($value, 2)),
            Column::make('best_seller', 'Favourite'),
            Column::make('misc')->fallback('N/A')
        ];
    }

    protected function filters(): array
    {
        return [
            Filter::make('name'),
            SelectFilter::make('status', 'availability'),
            // QueryFilter::make('id')->query(fn (Builder $builder, $value) => $builder->where('id', '<', $value)),
        ];
    }

    protected function sorts(): array
    {
        return [
            Sort::make('created_at', 'newest')->desc()->default(),
            Sort::make('created_at', 'oldest')->asc(),
        ];
    }

    protected function actions(): array
    {
        return [
            // PageAction::make('add')->label('Add User'),
            // BulkAction::make('delete')->label('Delete Users'),
        ];
    }
}