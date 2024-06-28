<?php

namespace Conquest\Table\Tests\Feature;

use Conquest\Table\Tests\Testcase;

class PreferenceTableTest extends Testcase
{
    protected $notPreferenced = [
        'id',
        'role'
    ];

    protected $notDefaultPreferences = [
        'name',
        'updated_at'
    ];

    protected $defaultPreferences = [
        'email',
        'created_at',
    ];

    protected function preferences()
    {
        return array_merge($this->notDefaultPreferences, $this->defaultPreferences);
    }

    protected function activeOnNone()
    {
        return array_merge($this->notPreferenced, $this->defaultPreferences);
    }
    
    public function test_load_table()
    {
        /** All tests are performed as endpoints, not making */
        $content = $this->get('/preference')->assertStatus(200)->getContent();

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

    public function test_table_has_correct_defaults()
    {
        $content = $this->get('/preference')->assertStatus(200)->getContent();
        $table = json_decode($content)->table;

        foreach ($table->preference_cols as $pref) {
            if (in_array($pref->name, $this->activeOnNone())) {
                $this->assertTrue($pref->active);
            }
            else {
                $this->assertFalse($pref->active);
            }
        }
    }

    public function test_table_has_preference()
    {
        $key = 'name';
        $content = $this->get("/preference?prefs=$key")->assertStatus(200)->getContent();
        $table = json_decode($content)->table;

        foreach ($table->preference_cols as $pref) {
            if (in_array($pref->name, $this->notPreferenced)) {
                $this->assertTrue($pref->active);
            }
            else if ($pref->name === $key) {
                $this->assertTrue($pref->active);
            }
            else {
                $this->assertFalse($pref->active);
            }
        }
    }
}