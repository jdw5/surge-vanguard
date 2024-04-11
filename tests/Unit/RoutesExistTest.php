<?php

namespace Jdw5\Vanguard\Tests\Unit;

use Jdw5\Vanguard\Tests\TestCase;

class RoutesExistTest extends TestCase
{
    public function test_routes_exist()
    {
        $this->get('/')->assertStatus(200);
    }
}