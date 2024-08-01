<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Http\Request;
use Workbench\App\Models\Product;
use Workbench\App\Tables\ProductTable;

class Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'table' => ProductTable::make(),
        ]);
    }

    public function show(Request $request, Product $product)
    {
        return response()->json([
            'product' => $product,
        ]);
    }

    public function edit(Request $request, Product $product)
    {
        return response()->json([
            'product' => $product,
        ]);
    }

    public function page(Request $request)
    {
        // A new page
    }

    public function handle(Request $request)
    {
        // return UserTab::handle($request);
    }
}
