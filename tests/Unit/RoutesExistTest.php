<?php

namespace Conquest\Table\Tests\Unit;

use Conquest\Table\Tests\TestCase;

class RoutesExistTest extends TestCase
{
    public function test_routes_exist()
    {
        $this->get('/basic')->assertStatus(200);
    }
}