<<<<<<< HEAD
<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workbench\App\Tables\ProductTable;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'table' => ProductTable::make()
        ]);
    }

    public function handle(Request $request)
    {
        // return UserTab::handle($request);
    }
}
=======
<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workbench\App\Tables\ProductTable;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // dd(ProductTable::make()->toArray());
        return response()->json([
            'table' => ProductTable::make(),
        ]);
    }

    public function handle(Request $request)
    {
        // return UserTab::handle($request);
    }
}
>>>>>>> 51034100cdea01f9e60c0a73c0cfd889fc1fe146
