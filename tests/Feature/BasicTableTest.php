<?php

namespace Conquest\Table\Tests\Feature;

use Conquest\Table\Tests\Testcase;

class BasicTableTest extends Testcase
{
    public function test_load_table()
    {
        /** All tests are performed as endpoints, not making */
        $content = $this->get('/basic')->assertStatus(200)->getContent();
        $this->assertJson($content);
        $table = json_decode($content)->table;
        $this->assertEquals($table->meta->per_page, 10);
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
    }

    public function test_load_table_with_sort()
    {
        $content = $this->get('/basic?sort=oldest')->assertStatus(200)->getContent();
        $table = json_decode($content)->table;
        $this->assertEquals($table->refinements->sorts->newest->active, false);
        $this->assertEquals($table->refinements->sorts->oldest->active, true);
        // Check that it's actually applied
    }

    public function test_admin_name_filter()
    {
        $content = $this->get('/basic?name=admin')->assertStatus(200)->getContent();
        $table = json_decode($content)->table;
        $this->assertEquals($table->refinements->filters->name->active, true);
        $this->assertEquals($table->meta->total, 1);
    }

    public function test_invalid_admin_name_filter()
    {
        $content = $this->get('/basic?name=adminssss')->assertStatus(200)->getContent();
        $table = json_decode($content)->table;
        $this->assertEquals($table->refinements->filters->name->active, true);
        $this->assertEquals($table->meta->total, 0);
        $this->assertEquals($table->meta->empty, true);
    }

    public function test_aliased_select_filter_single()
    {
        $content = $this->get('/basic?type=2')->assertStatus(200)->getContent();
        $table = json_decode($content)->table;
        $this->assertEquals($table->refinements->filters->type->active, true);
        $this->assertEquals($table->meta->total, 1);
        $this->assertEquals($table->meta->empty, false);
    }

    public function test_aliased_select_filter_array()
    {
        $content = $this->get('/basic?type=1,2')->assertStatus(200)->getContent();
        $table = json_decode($content)->table;
        $this->assertEquals($table->refinements->filters->type->active, true);
        $this->assertEquals($table->meta->total, 5);
    }

    public function test_aliased_select_filter_all()
    {
        $content = $this->get('/basic?type=0,1,2')->assertStatus(200)->getContent();
        $table = json_decode($content)->table;
        $this->assertEquals($table->refinements->filters->type->active, true);
        $this->assertEquals($table->meta->total, 12);
    }

    public function test_aliased_select_filter_invalid()
    {
        $content = $this->get('/basic?type=4')->assertStatus(200)->getContent();
        $table = json_decode($content)->table;
        $this->assertEquals($table->refinements->filters->type->active, true);
        $this->assertEquals($table->meta->total, 0);
        $this->assertEquals($table->meta->empty, true);
    }

    public function test_query_filter()
    {
        $content = $this->get('/basic?id=2')->assertStatus(200)->getContent();
        $table = json_decode($content)->table;
        $this->assertEquals($table->refinements->filters->id->active, true);
        $this->assertEquals($table->meta->total, 1);
    }

    public function test_query_filter_invalid_shows_all()
    {
        $content = $this->get('/basic?id=s')->assertStatus(200)->getContent();
        $table = json_decode($content)->table;
        $this->assertEquals($table->refinements->filters->id->active, true);
        $this->assertEquals($table->meta->total, 12);
        $this->assertEquals($table->meta->empty, false);
    }

    public function test_multiple_filters()
    {
        $content = $this->get('/basic?id=6&type=1,2')->assertStatus(200)->getContent();
        $table = json_decode($content)->table;
        $this->assertEquals($table->refinements->filters->id->active, true);
        $this->assertEquals($table->refinements->filters->type->active, true);
        $this->assertEquals($table->meta->total, 3);
        $this->assertEquals($table->meta->empty, false);
    }

    public function test_paginates()
    {
        $content = $this->get('/basic?page=2')->assertStatus(200)->getContent();
        $table = json_decode($content)->table;
        $this->assertEquals($table->meta->current_page, 2);
        $this->assertEquals($table->meta->from, 11);
        $this->assertEquals($table->meta->to, 12);
        $this->assertEquals($table->meta->per_page, 10);
        $this->assertEquals($table->meta->total, 12);
    }
}