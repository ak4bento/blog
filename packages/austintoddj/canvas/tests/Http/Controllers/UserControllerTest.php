<?php

namespace Canvas\Tests\Http\Controllers;

use Canvas\Models\Post;
use Canvas\Models\User;
use Canvas\Models\View;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Uuid\Uuid;

/**
 * Class UserControllerTest.
 *
 * @covers \Canvas\Http\Controllers\UserController
 * @covers \Canvas\Http\Requests\UserRequest
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testListAllUsers(): void
    {
        $response = $this->actingAs($this->admin, 'canvas')
                         ->getJson('canvas/api/users')
                         ->assertSuccessful();

        $this->assertInstanceOf(User::class, $response->getOriginalContent()->first());

        $this->assertInstanceOf(LengthAwarePaginator::class, $response->getOriginalContent());

        $this->assertCount(3, $response->getOriginalContent());
    }

    public function testCreateDataForUser(): void
    {
        $response = $this->actingAs($this->admin, 'canvas')
                         ->getJson('canvas/api/users/create')
                         ->assertSuccessful();

        $this->assertInstanceOf(User::class, $response->getOriginalContent());
    }

    public function testExistingUserData(): void
    {
        $response = $this->actingAs($this->admin, 'canvas')
                         ->getJson("canvas/api/users/{$this->contributor->id}")
                         ->assertSuccessful();

        $this->assertTrue($this->contributor->is($response->getOriginalContent()));
    }

    public function testListPostsForUser(): void
    {
        $post = factory(Post::class)->create([
            'user_id' => $this->admin->id,
        ]);

        factory(View::class)->create([
            'post_id' => $post->id,
        ]);

        $response = $this->actingAs($this->admin, 'canvas')
                         ->getJson("canvas/api/users/{$this->admin->id}/posts")
                         ->assertSuccessful();

        $this->assertInstanceOf(Post::class, $response->getOriginalContent()->first());

        $this->assertInstanceOf(LengthAwarePaginator::class, $response->getOriginalContent());

        $this->assertCount(1, $response->getOriginalContent());
    }

    public function testUserNotFound(): void
    {
        $this->actingAs($this->admin, 'canvas')
             ->getJson('canvas/api/users/not-a-user')
             ->assertNotFound();
    }

    public function testStoreNewUser(): void
    {
        $data = [
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Name',
            'email' => 'email@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->actingAs($this->admin, 'canvas')
                         ->postJson("canvas/api/users/{$data['id']}", $data)
                         ->assertSuccessful();

        $this->assertInstanceOf(User::class, $response->getOriginalContent()['user']);

        $this->assertSame($data['id'], $response->getOriginalContent()['user']->id);
    }

    public function testDeletedUsersCanBeRefreshed(): void
    {
        $deletedUser = factory(User::class)->create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Deleted User',
            'email' => 'email@example.com',
            'deleted_at' => now(),
        ]);

        $data = [
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Deleted User',
            'email' => 'email@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->actingAs($this->admin, 'canvas')
                         ->postJson("canvas/api/users/{$data['id']}", $data)
                         ->assertSuccessful();

        $this->assertInstanceOf(User::class, $response->getOriginalContent()['user']);

        $this->assertSame($deletedUser['id'], $response->getOriginalContent()['user']->id);
    }

    public function testUpdateExistingUser(): void
    {
        $user = factory(User::class)->create();

        $data = [
            'name' => 'New name',
            'email' => 'new-email@example.com',
        ];

        $response = $this->actingAs($this->admin, 'canvas')
                         ->postJson("canvas/api/users/{$user->id}", $data)
                         ->assertSuccessful()
                         ->assertJsonFragment([
                             'id' => $user->id,
                             'name' => $data['name'],
                             'email' => $data['email'],
                         ]);

        $this->assertInstanceOf(User::class, $response->getOriginalContent()['user']);

        $this->assertSame($data['email'], $response->getOriginalContent()['user']->email);
    }

    public function testInvalidPasswordCombinationsAreValidated(): void
    {
        $data = [
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Name',
            'email' => 'email@example.com',
            'password' => 'password',
            'password_confirmation' => 'not-a-match',
        ];

        $this->actingAs($this->admin, 'canvas')
             ->postJson("canvas/api/users/{$data['id']}", $data)
             ->assertStatus(422)
             ->assertJsonStructure([
                 'errors' => [
                     'password',
                 ],
             ]);
    }

    public function testShortPasswordsAreValidated(): void
    {
        $data = [
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Name',
            'email' => 'email@example.com',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ];

        $this->actingAs($this->admin, 'canvas')
             ->postJson("canvas/api/users/{$data['id']}", $data)
             ->assertStatus(422)
             ->assertJsonStructure([
                 'errors' => [
                     'password',
                 ],
             ]);
    }

    public function testDuplicateUsernamesAreValidated(): void
    {
        $this->actingAs($this->admin, 'canvas')
             ->postJson("canvas/api/users/{$this->admin->id}", [
                 'name' => $this->admin->name,
                 'email' => $this->admin->email,
                 'username' => $this->editor->username,
             ])
             ->assertStatus(422)
             ->assertJsonStructure([
                 'errors' => [
                     'username',
                 ],
             ]);
    }

    public function testDuplicateEmailsAreValidated(): void
    {
        $this->actingAs($this->admin, 'canvas')
             ->postJson("canvas/api/users/{$this->admin->id}", [
                 'name' => $this->admin->name,
                 'email' => $this->editor->email,
             ])
             ->assertStatus(422)
             ->assertJsonStructure([
                 'errors' => [
                     'email',
                 ],
             ]);
    }

    public function testInvalidEmailsAreValidated(): void
    {
        $this->actingAs($this->admin, 'canvas')
             ->postJson("canvas/api/users/{$this->admin->id}", [
                 'name' => $this->admin->name,
                 'email' => 'not-an-email',
             ])
             ->assertStatus(422)
             ->assertJsonStructure([
                 'errors' => [
                     'email',
                 ],
             ]);
    }

    public function testUsersCannotDeleteTheirOwnAccount(): void
    {
        $this->actingAs($this->admin, 'canvas')
             ->deleteJson("canvas/api/users/{$this->admin->id}")
             ->assertForbidden();
    }

    public function testDeleteExistingUser(): void
    {
        $user = factory(User::class)->create();

        $this->actingAs($this->admin, 'canvas')
             ->deleteJson('canvas/api/users/not-a-user')
             ->assertNotFound();

        $this->actingAs($this->admin, 'canvas')
             ->deleteJson("canvas/api/users/{$user->id}")
             ->assertSuccessful()
             ->assertNoContent();

        $this->assertSoftDeleted('canvas_users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }
}
