<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workbench\App\Tables\UserTable;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'table' => UserTable::make()
        ]);
    }

    public function handle(Request $request)
    {
        // return UserTab::handle($request);
    }
}