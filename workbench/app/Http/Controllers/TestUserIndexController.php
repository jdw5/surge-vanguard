<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TestUserIndexController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'message' => 'Hello, World!',
        ]);
    }
}