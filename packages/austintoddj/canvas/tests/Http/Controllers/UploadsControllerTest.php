<?php

namespace Canvas\Tests\Http\Controllers;

use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class UploadsControllerTest.
 *
 * @covers \Canvas\Http\Controllers\UploadsController
 */
class UploadsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testEmptyUploadIsValidated(): void
    {
        Storage::fake(config('canvas.storage_disk'));

        $this->actingAs($this->admin, 'canvas')
             ->postJson('canvas/api/uploads', [null])
             ->assertStatus(400);
    }

    public function testUploadedImageCanBeStored(): void
    {
        Storage::fake(config('canvas.storage_disk'));

        $response = $this->actingAs($this->admin, 'canvas')
                         ->postJson('canvas/api/uploads', [$file = UploadedFile::fake()->image('1.jpg')])
                         ->assertSuccessful();

        $path = sprintf('%s/%s/%s', config('canvas.storage_path'), 'images', $file->hashName());

        $this->assertSame(
            $response->getOriginalContent(),
            Storage::disk(config('canvas.storage_disk'))->url($path)
        );

        $this->assertIsString($response->getContent());

        Storage::disk(config('canvas.storage_disk'))->assertExists($path);
    }

    public function testDeleteUploadedImage(): void
    {
        Storage::fake(config('canvas.storage_disk'));

        $this->actingAs($this->admin, 'canvas')
             ->delete('canvas/api/uploads', [
                 null,
             ])->assertStatus(400);

        $this->actingAs($this->admin, 'canvas')
             ->deleteJson('canvas/api/uploads', [$file = UploadedFile::fake()->image('1.jpg')])
             ->assertSuccessful();

        $path = sprintf('%s/%s/%s', config('canvas.storage_path'), 'images', $file->hashName());

        Storage::disk(config('canvas.storage_disk'))->assertMissing($path);
    }
}
