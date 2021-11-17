<?php

namespace Canvas\Tests\Http\Controllers;

use Canvas\Models\Post;
use Canvas\Models\Tag;
use Canvas\Models\View;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Uuid\Uuid;

/**
 * Class TagControllerTest.
 *
 * @covers \Canvas\Http\Controllers\TagController
 * @covers \Canvas\Http\Requests\TagRequest
 */
class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testListAllTags(): void
    {
        factory(Tag::class, 2)->create();

        $response = $this->actingAs($this->admin, 'canvas')
                         ->getJson('canvas/api/tags')
                         ->assertSuccessful();

        $this->assertInstanceOf(Tag::class, $response->getOriginalContent()->first());

        $this->assertInstanceOf(LengthAwarePaginator::class, $response->getOriginalContent());

        $this->assertCount(2, $response->getOriginalContent());
    }

    public function testCreateDataForTag(): void
    {
        $response = $this->actingAs($this->admin, 'canvas')
                         ->getJson('canvas/api/tags/create')
                         ->assertSuccessful();

        $this->assertInstanceOf(Tag::class, $response->getOriginalContent());
    }

    public function testExistingTagData(): void
    {
        $tag = factory(Tag::class)->create();

        $response = $this->actingAs($this->admin, 'canvas')
                         ->getJson("canvas/api/tags/{$tag->id}")
                         ->assertSuccessful();

        $this->assertTrue($tag->is($response->getOriginalContent()));
    }

    public function testListPostsForTag(): void
    {
        $tag = factory(Tag::class)->create();
        $post = factory(Post::class)->create();

        factory(View::class)->create([
            'post_id' => $post->id,
        ]);

        $tag->posts()->sync([$post->id]);

        $response = $this->actingAs($this->admin, 'canvas')
                         ->getJson("canvas/api/tags/{$tag->id}/posts")
                         ->assertSuccessful();

        $this->assertInstanceOf(Post::class, $response->getOriginalContent()->first());

        $this->assertInstanceOf(LengthAwarePaginator::class, $response->getOriginalContent());

        $this->assertCount(1, $response->getOriginalContent());
    }

    public function testTagNotFound(): void
    {
        $this->actingAs($this->admin, 'canvas')
             ->getJson('canvas/api/tags/not-a-tag')
             ->assertNotFound();
    }

    public function testStoreNewTag(): void
    {
        $data = [
            'id' => Uuid::uuid4()->toString(),
            'name' => 'A new tag',
            'slug' => 'a-new-tag',
        ];

        $response = $this->actingAs($this->admin, 'canvas')
                         ->postJson("canvas/api/tags/{$data['id']}", $data)
                         ->assertSuccessful();

        $this->assertInstanceOf(Tag::class, $response->getOriginalContent()->first());

        $this->assertSame($data['id'], $response->getOriginalContent()->id);
    }

    public function testDeletedTagsCanBeRefreshed(): void
    {
        $deletedTag = factory(Tag::class)->create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'A deleted tag',
            'slug' => 'a-deleted-tag',
            'user_id' => $this->editor->id,
            'deleted_at' => now(),
        ]);

        $data = [
            'id' => Uuid::uuid4()->toString(),
            'name' => $deletedTag->name,
            'slug' => $deletedTag->slug,
        ];

        $response = $this->actingAs($this->admin, 'canvas')
                         ->postJson("canvas/api/tags/{$data['id']}", $data)
                         ->assertSuccessful();

        $this->assertInstanceOf(Tag::class, $response->getOriginalContent()->first());

        $this->assertSame($deletedTag['id'], $response->getOriginalContent()->id);
    }

    public function testUpdateExistingTag(): void
    {
        $tag = factory(Tag::class)->create();

        $data = [
            'name' => 'An updated tag',
            'slug' => 'an-updated-tag',
        ];

        $response = $this->actingAs($this->admin, 'canvas')
                         ->postJson("canvas/api/tags/{$tag->id}", $data)
                         ->assertSuccessful();

        $this->assertInstanceOf(Tag::class, $response->getOriginalContent()->first());

        $this->assertSame($data['slug'], $response->getOriginalContent()->slug);
    }

    public function testInvalidSlugsAreValidated(): void
    {
        $tag = factory(Tag::class)->create();

        $this->actingAs($this->admin, 'canvas')
             ->postJson("canvas/api/tags/{$tag->id}", [
                 'name' => 'A new tag',
                 'slug' => 'a new.slug',
             ])
             ->assertStatus(422)
             ->assertJsonStructure([
                 'errors' => [
                     'slug',
                 ],
             ]);
    }

    public function testDeleteExistingTag(): void
    {
        $tag = factory(Tag::class)->create();

        $this->actingAs($this->admin, 'canvas')
             ->deleteJson('canvas/api/tags/not-a-tag')
             ->assertNotFound();

        $this->actingAs($this->admin, 'canvas')
             ->deleteJson("canvas/api/tags/{$tag->id}")
             ->assertSuccessful()
             ->assertNoContent();

        $this->assertSoftDeleted('canvas_tags', [
            'id' => $tag->id,
            'slug' => $tag->slug,
        ]);
    }

    public function testDeSyncPostRelationship(): void
    {
        $tag = factory(Tag::class)->create();
        $post = factory(Post::class)->create();

        $tag->posts()->sync([$post->id]);

        $this->assertDatabaseHas('canvas_posts_tags', [
            'post_id' => $post->id,
            'tag_id' => $tag->id,
        ]);

        $this->assertCount(1, $tag->posts);

        $this->actingAs($this->admin, 'canvas')
             ->deleteJson("canvas/api/posts/{$post->id}")
             ->assertSuccessful()
             ->assertNoContent();

        $this->assertSoftDeleted('canvas_posts', [
            'id' => $post->id,
            'slug' => $post->slug,
        ]);

        $this->assertDatabaseMissing('canvas_posts_tags', [
            'post_id' => $post->id,
            'tag_id' => $tag->id,
        ]);

        $this->assertCount(0, $tag->refresh()->posts);
    }
}
