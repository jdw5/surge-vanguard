<?php

namespace Jdw5\Vanguard\Tests\Feature;

use Jdw5\Vanguard\Tests\Testcase;
use Workbench\App\Models\TestUser;

class LoadTableTest extends Testcase
{
    public function test_load_table()
    {
        $users = TestUser::get();
        
        $count = $users->count();
        $this->assertEquals($count, 100);
        // assert($count, 100);
        // $this->get('/')->assertStatus(200);
    }
}