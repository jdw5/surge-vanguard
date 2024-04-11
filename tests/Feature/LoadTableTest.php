<?php

namespace Jdw5\Vanguard\Tests\Feature;

use Jdw5\Vanguard\Tests\Testcase;

class LoadTableTest extends Testcase
{
    public function test_load_table()
    {
        $this->get('/')
            ->assertStatus(200);
    }
}