<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Workbench\App\Http\Controllers\Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/products', [Controller::class, 'index'])->name('product.index');
Route::get('/page', [Controller::class, 'page'])->name('page.index');
Route::get('/product/{product}', [Controller::class, 'show'])->name('product.show');

Route::get('/table', function () {
    return Inertia::render('Table');
});
