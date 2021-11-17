<?php

namespace Canvas\Tests\Mail;

use Canvas\Mail\ResetPassword;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

/**
 * Class ResetPasswordTest.
 *
 * @covers \Canvas\Mail\ResetPassword
 */
class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testInstantiation(): void
    {
        $token = Str::random(60);

        $mailable = new ResetPassword($token);

        $this->assertInstanceOf(ResetPassword::class, $mailable->build());
    }
}
