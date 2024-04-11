<?php

namespace Jdw5\Vanguard\Tests\Feature;

use Jdw5\Vanguard\Tests\Testcase;
use Workbench\App\Models\TestUser;

class BasicTableTest extends Testcase
{
    public function test_load_table()
    {
        /** All tests are performed as endpoints, not making */
        $content = $this->get('/basic')->assertStatus(200)->getContent();
        $this->assertJson($content);
        $content = json_decode($content);
        $this->assertEquals($content->table->meta->per_page, 10);
        var_dump($content);
    }
}