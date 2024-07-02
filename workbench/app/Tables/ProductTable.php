<?php

namespace Workbench\App\Tables;

use Carbon\Carbon;
use Conquest\Table\Table;
use Conquest\Table\Sorts\Sort;
use Conquest\Core\Options\Option;
use Workbench\App\Models\Product;
use Conquest\Table\Columns\Column;
use Conquest\Table\Filters\Filter;
use Workbench\App\Models\TestUser;
use Conquest\Table\Actions\BaseAction;
use Conquest\Table\Actions\BulkAction;
use Conquest\Table\Actions\PageAction;
use Conquest\Table\Columns\DateColumn;
use Conquest\Table\Columns\TextColumn;
use Conquest\Table\Filters\DateFilter;
use Conquest\Table\Filters\QueryFilter;
use Conquest\Table\Actions\InlineAction;
use Conquest\Table\Filters\SelectFilter;
use Conquest\Table\Columns\BooleanColumn;
use Conquest\Table\Columns\NumericColumn;
use Conquest\Table\Filters\BooleanFilter;

final class ProductTable extends Table
{
    protected $resource = Product::class;
    protected $search = ['name', 'description'];
    protected $pagination = [10, 50, 100];

    protected function columns(): array
    {
        return [
            Column::make('public_id')->asKey()->hide(),
            TextColumn::make('name')->sort(),
            TextColumn::make('description')->fallback('No description')->sort(),
            DateColumn::make('created_at')->format('d M Y'),
            NumericColumn::make('price')->transform(fn ($value) => '$' . number_format($value, 2)),
            BooleanColumn::make('best_seller', 'Favourite'),
            Column::make('misc')->fallback('N/A')
        ];
    }

    protected function filters(): array
    {
        return [
            Filter::make('name')->like(),
            BooleanFilter::make('best_seller', 'availability', 1),
            DateFilter::make('created_at', 'before')->operator('<='),
            // SelectFilter::make('price', 'price-max')->options([
            //     Option::make(100),
            //     Option::make(500),
            //     Option::make(1000),
            //     Option::make(5000),
            // ]),
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