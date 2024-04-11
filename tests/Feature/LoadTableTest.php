<?php

namespace Jdw5\Vanguard\Tests\Feature;

use Jdw5\Vanguard\Tests\Testcase;
use Workbench\App\Models\TestUser;

class LoadTableTest extends Testcase
{
    public function test_load_table()
    {
        var_dump('Hi');
        $users = TestUser::get();
        
        $this->assertIsObject($users);
        // $this->get('/')->assertStatus(200);
    }
}