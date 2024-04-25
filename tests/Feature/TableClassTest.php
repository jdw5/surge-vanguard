<?php

namespace Jdw5\Vanguard\Tests\Feature;

use Jdw5\Vanguard\Tests\Testcase;
use Workbench\App\Models\TestUser;
use Workbench\App\Tables\BasicTable;

/** These tests verify the desired behaviours of the table, such as 
 * method overriding and defaults
 */
class TableClassTest extends Testcase
{
    public function test_paginates_by_default()
    {
        $table = BasicTable::make();
        $this->assertEquals($table->getPaginateType(), null);
        $this->assertEquals($table->getMeta()['per_page'], 10);
    }


    public function test_paginates_overriden()
    {
        $table = BasicTable::make()->paginate(11);
        $this->assertEquals($table->getPaginateType(), 'paginate');
        $this->assertEquals($table->getMeta()['per_page'], 11);
    }

    public function test_cursor_overriden()
    {
        $table = BasicTable::make()->cursorPaginate(11);
        $this->assertEquals($table->getPaginateType(), 'cursor');
        $this->assertEquals($table->getMeta()['per_page'], 11);
    }

    public function test_query_override()
    {
        $table = BasicTable::make(TestUser::select('id'));
        $this->assertNotEquals($table->getFirstRecord()['id'], NULL);
        $this->assertEquals($table->getFirstRecord()['name'], NULL);
    }

    public function test_collection_get()
    {
        $table = BasicTable::make()->get();
        $this->assertEquals($table->getPaginateType(), 'get');
        $this->assertEquals(array_key_exists('per_page', $table->getMeta()), false);
    }
}