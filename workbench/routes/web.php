<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\TestUserIndexController;

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

Route::get('/', TestUserIndexController::class)->name('test_users.index');
