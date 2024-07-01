<?php

namespace Conquest\Table\Console\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Console\GeneratorCommand;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:table')]
class TableMakeCommand extends GeneratorCommand
{
    use CreatesMatchingTest;

    protected $name = 'make:table';

    protected $description = 'Create a new table class';

    protected $type = 'Table';

    protected function getStub()
    {
        $stub = '/stubs/table.php.stub';

        return $this->resolveStubPath($stub);
    }

    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.'/../../..'.$stub;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Tables';
    }

    protected function buildClass($name)
    {
        $tableNamespace = $this->getNamespace($name);

        $replace = [];

        $replace["use {$tableNamespace};\n"] = '';

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name),
        );
    }

    protected function parseModel($table)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $table)) {
            throw new InvalidArgumentException('Table name contains invalid characters.');
        }

        return $this->qualifyModel($table);
    }

    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the table already exists'],
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Create a new model for the table'],
        ];
    }
}
