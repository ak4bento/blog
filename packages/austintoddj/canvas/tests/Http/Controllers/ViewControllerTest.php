<?php

namespace Canvas\Tests\Http\Controllers;

use Canvas\Tests\TestCase;

/**
 * Class ViewControllerTest.
 *
 * @covers \Canvas\Http\Controllers\ViewController
 */
class ViewControllerTest extends TestCase
{
    /** @test */
    public function testScriptVariables(): void
    {
        $this->withoutMix();

        $this->actingAs($this->admin, 'canvas')
             ->get(config('canvas.path'))
             ->assertSuccessful()
             ->assertViewIs('canvas::layout')
             ->assertViewHas('jsVars')
             ->assertSee('canvas');
    }
}
