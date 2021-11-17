<?php

namespace Canvas\Tests\Http\Controllers;

use Canvas\Models\Post;
use Canvas\Models\View;
use Canvas\Models\Visit;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class HomeControllerTest.
 *
 * @covers \Canvas\Http\Controllers\StatsController
 * @covers \Canvas\Services\StatsAggregator
 */
class StatsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserStatsAreFetchedByDefault(): void
    {
        factory(Post::class, 3)->create([
            'user_id' => $this->admin->id,
        ])->each(function ($post) {
            $post->visits()->createMany(factory(Visit::class, 2)->make()->toArray());
            $post->views()->createMany(factory(View::class, 3)->make()->toArray());
        });

        factory(Post::class, 2)->create([
            'user_id' => $this->contributor->id,
        ])->each(function ($post) {
            $post->visits()->createMany(factory(Visit::class, 1)->make()->toArray());
            $post->views()->createMany(factory(View::class, 2)->make()->toArray());
        });

        $this->actingAs($this->admin, 'canvas')
             ->getJson('canvas/api/stats')
             ->assertSuccessful()
             ->assertJsonStructure([
                 'views',
                 'visits',
                 'graph' => [
                     'views',
                     'visits',
                 ],
             ])
             ->assertJsonFragment([
                 'views' => 9,
                 'visits' => 6,
             ]);
    }

    public function testAllPostStatsCanBeFetchedWithAGivenQueryScope(): void
    {
        factory(Post::class, 3)->create([
            'user_id' => $this->admin->id,
        ])->each(function ($post) {
            $post->visits()->createMany(factory(Visit::class, 2)->make()->toArray());
            $post->views()->createMany(factory(View::class, 3)->make()->toArray());
        });

        factory(Post::class, 2)->create([
            'user_id' => $this->contributor->id,
        ])->each(function ($post) {
            $post->visits()->createMany(factory(Visit::class, 1)->make()->toArray());
            $post->views()->createMany(factory(View::class, 2)->make()->toArray());
        });

        $this->actingAs($this->admin, 'canvas')
             ->getJson('canvas/api/stats?scope=all')
             ->assertSuccessful()
             ->assertJsonStructure([
                 'views',
                 'visits',
                 'graph' => [
                     'views',
                     'visits',
                 ],
             ])
             ->assertJsonFragment([
                 'views' => 13,
                 'visits' => 8,
             ]);
    }
}
