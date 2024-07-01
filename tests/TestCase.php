<?php

namespace Conquest\Table\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Orchestra\Testbench\Concerns\WithWorkbench;
use function Orchestra\Testbench\workbench_path;

class TestCase extends Orchestra
{
    use WithWorkbench;
    
    protected function setUp(): void
    {
        parent::setUp();
    }
}
