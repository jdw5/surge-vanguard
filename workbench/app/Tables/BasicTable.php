<?php

namespace Workbench\App\Tables;

use Jdw5\Vanguard\Table\Columns\Column;
use Jdw5\Vanguard\Table\Table;
use Workbench\App\Models\TestUser;

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
            Column::make('name'),
            Column::make('email'),
            Column::make('created_at')->transform(fn ($value) => $value->format('d/m/Y H:i:s')),
            Column::make('role')->label('User Role')
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
        ];
    }
}