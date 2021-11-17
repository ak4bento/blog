<?php

namespace Canvas\Tests\Http\Controllers;

use Canvas\Models\Post;
use Canvas\Models\Tag;
use Canvas\Models\Topic;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class SearchControllerTest.
 *
 * @covers \Canvas\Http\Controllers\SearchController
 */
class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAContributorCanOnlySearchTheirOwnPosts(): void
    {
        factory(Post::class, 3)->create([
            'user_id' => $this->contributor->id,
        ]);

        factory(Post::class)->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->contributor, 'canvas')
                         ->getJson('canvas/api/search/posts')
                         ->assertSuccessful()
                         ->assertJsonCount(3);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('title', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('type', $response[0]);
        $this->assertSame('Post', $response[0]['type']);
        $this->assertArrayHasKey('route', $response[0]);
        $this->assertSame('edit-post', $response[0]['route']);
    }

    public function testAnEditorCanSearchAllPosts(): void
    {
        factory(Post::class, 3)->create([
            'user_id' => $this->editor->id,
        ]);

        factory(Post::class)->create([
            'user_id' => $this->contributor->id,
        ]);

        $response = $this->actingAs($this->editor, 'canvas')
                         ->getJson('canvas/api/search/posts')
                         ->assertSuccessful()
                         ->assertJsonCount(4);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('title', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('type', $response[0]);
        $this->assertSame('Post', $response[0]['type']);
        $this->assertArrayHasKey('route', $response[0]);
        $this->assertSame('edit-post', $response[0]['route']);
    }

    public function testAnAdminCanSearchAllPosts(): void
    {
        factory(Post::class, 3)->create([
            'user_id' => $this->editor->id,
        ]);

        factory(Post::class)->create([
            'user_id' => $this->contributor->id,
        ]);

        $response = $this->actingAs($this->admin, 'canvas')
                         ->getJson('canvas/api/search/posts')
                         ->assertSuccessful()
                         ->assertJsonCount(4);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('title', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('type', $response[0]);
        $this->assertSame('Post', $response[0]['type']);
        $this->assertArrayHasKey('route', $response[0]);
        $this->assertSame('edit-post', $response[0]['route']);
    }

    public function testAnAdminCanSearchAllTags(): void
    {
        factory(Tag::class, 2)->create();

        $response = $this->actingAs($this->admin, 'canvas')
                         ->getJson('canvas/api/search/tags')
                         ->assertSuccessful()
                         ->assertJsonCount(2);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('type', $response[0]);
        $this->assertSame('Tag', $response[0]['type']);
        $this->assertArrayHasKey('route', $response[0]);
        $this->assertSame('edit-tag', $response[0]['route']);
    }

    public function testAnAdminCanSearchAllTopics(): void
    {
        factory(Topic::class, 3)->create();

        $response = $this->actingAs($this->admin, 'canvas')
                         ->getJson('canvas/api/search/topics')
                         ->assertSuccessful()
                         ->assertJsonCount(3);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('type', $response[0]);
        $this->assertSame('Topic', $response[0]['type']);
        $this->assertArrayHasKey('route', $response[0]);
        $this->assertSame('edit-topic', $response[0]['route']);
    }

    public function testAnAdminCanSearchAllUsers(): void
    {
        $response = $this->actingAs($this->admin, 'canvas')
                         ->getJson('canvas/api/search/users')
                         ->assertSuccessful()
                         ->assertJsonCount(3);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('type', $response[0]);
        $this->assertSame('User', $response[0]['type']);
        $this->assertArrayHasKey('route', $response[0]);
        $this->assertSame('edit-user', $response[0]['route']);
    }
}
