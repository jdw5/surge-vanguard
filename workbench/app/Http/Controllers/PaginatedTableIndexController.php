<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workbench\App\Tables\PaginatedTable;

class PaginatedTableIndexController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'table' => PaginatedTable::make(),
        ]);
    }
}
