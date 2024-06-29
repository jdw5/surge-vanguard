<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\BasicTableIndexController;
use Workbench\App\Http\Controllers\ActionTableIndexController;
use Workbench\App\Http\Controllers\PaginatedTableIndexController;
use Workbench\App\Http\Controllers\PreferenceTableIndexController;
use Workbench\App\Http\Controllers\ProductController;

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

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/basic', BasicTableIndexController::class)->name('basic.index');
Route::get('/action', ActionTableIndexController::class)->name('action.index');
Route::get('/paginated', PaginatedTableIndexController::class)->name('paginated.index');
Route::get('/preference', PreferenceTableIndexController::class)->name('preference.index');
