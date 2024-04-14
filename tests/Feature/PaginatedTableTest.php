<?php

namespace Jdw5\Vanguard\Tests\Feature;

use Jdw5\Vanguard\Tests\Testcase;

class PaginatedTableTest extends Testcase
{
    public function test_load_table()
    {
        $content = $this->get('/paginated')->assertStatus(200)->getContent();
        $this->assertJson($content);
        $table = json_decode($content)->table;
        $this->assertEquals($table->recordKey, 'id');
        $this->assertEquals(count($table->actions->inline), 3);
        $this->assertEquals(count($table->actions->page), 1);
        $this->assertEquals(count($table->actions->bulk), 1);
        $this->assertEquals(count(get_object_vars($table->refinements->sorts)), 2);
        $this->assertEquals(count(get_object_vars($table->refinements->filters)), 3);

        $this->assertEquals($table->refinements->sorts->newest->active, true);
        $this->assertEquals($table->refinements->sorts->oldest->active, false);
        
        foreach ($table->refinements->filters as $key => $value) {
            $this->assertEquals($value->active, false);
        }

        $this->assertEquals($table->meta->per_page, 5);
        $this->assertEquals(count($table->paging_options->options), 4);
        $this->assertEquals($table->paging_options->key, 'count');
    }   

    public function test_change_showing_amount()
    {
        $content = $this->get('/paginated?count=10')->assertStatus(200)->getContent();
        $table = json_decode($content)->table;
        $this->assertEquals($table->meta->per_page, 10);
    }
}