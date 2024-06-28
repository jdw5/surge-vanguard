<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workbench\App\Tables\BasicTable;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'table' => BasicTable::make()
        ]);
    }
}