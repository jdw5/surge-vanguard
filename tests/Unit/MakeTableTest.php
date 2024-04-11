<?php

namespace Jdw5\Vanguard\Tests\Unit;

use Jdw5\Vanguard\Tests\TestCase;

class MakeTableTest extends TestCase
{
    public function test_makes_table()
    {
        $this->artisan('make:table TestUser')
            ->assertExitCode(0);        
    }
}