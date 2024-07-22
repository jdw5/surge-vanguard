<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use function Pest\Laravel\artisan;

beforeEach(function () {
    // Ensure the Tables directory exists
    File::makeDirectory(app()->basePath('app/Tables'), 0755, true, true);
});

afterEach(function () {
    // Clean up created files after each test
    File::deleteDirectory(app()->basePath('app/Tables'));
});

it('creates a new table class', function () {
    artisan('make:table', ['name' => 'UserTable'])->assertExitCode(0);

    $filePath = app()->basePath('app/Tables/UserTable.php');
    expect($filePath)->toBeReadableFile();
    expect(File::get($filePath))->toContain('class UserTable extends Table');
});

it('creates a table class with correct namespace', function () {
    artisan('make:table', ['name' => 'Admin/UserTable'])->assertExitCode(0);

    $filePath = app()->basePath('app/Tables/Admin/UserTable.php');
    expect($filePath)->toBeReadableFile();
    expect(File::get($filePath))->toContain('namespace App\Tables\Admin;');
});

it('does not overwrite an existing table class', function () {
    $filePath = app()->basePath('app/Tables/ExistingTable.php');
    File::put($filePath, 'Existing content');

    artisan('make:table', ['name' => 'ExistingTable'])->assertSuccessful();

    expect(File::get($filePath))->toBe('Existing content');
});

it('creates a table class with force option', function () {
    $filePath = app()->basePath('app/Tables/ExistingTable.php');
    File::put($filePath, 'Existing content');

    artisan('make:table', ['name' => 'ExistingTable', '--force' => true])->assertExitCode(0);

    expect(File::get($filePath))->toContain('class ExistingTable extends Table');
});