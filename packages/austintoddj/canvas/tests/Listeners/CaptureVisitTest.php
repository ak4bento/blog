<?php

namespace Canvas\Tests\Listeners;

use Canvas\Events\PostViewed;
use Canvas\Listeners\CaptureVisit;
use Canvas\Models\Post;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class CaptureVisitTest.
 *
 * @covers \Canvas\Listeners\CaptureVisit
 */
class CaptureVisitTest extends TestCase
{
    use RefreshDatabase;

    public function testInstantiation(): void
    {
        $post = factory(Post::class)->create();

        $event = new PostViewed($post);

        $listener = new CaptureVisit();

        $listener->handle($event);
        $listener->handle($event);

        $this->assertDatabaseHas('canvas_visits', [
            'post_id' => $post->id,
        ]);

        $this->assertCount(1, $post->visits);
    }

    public function testVisitsAreCountedByIpInSessionOncePerDay(): void
    {
        $post = factory(Post::class)->create();

        $event = new PostViewed($post);

        $listener = new CaptureVisit();

        $listener->handle($event);
        $listener->handle($event);

        $this->assertDatabaseHas('canvas_visits', [
            'post_id' => $post->id,
        ]);

        $this->assertCount(1, $post->visits);
    }
}
