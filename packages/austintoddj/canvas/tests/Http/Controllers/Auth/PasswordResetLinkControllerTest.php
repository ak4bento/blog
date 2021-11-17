<?php

namespace Canvas\Tests\Http\Controllers\Auth;

use Canvas\Mail\ResetPassword;
use Canvas\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * Class PasswordResetLinkControllerTest.
 *
 * @covers \Canvas\Http\Controllers\Auth\PasswordResetLinkController
 */
class PasswordResetLinkControllerTest extends TestCase
{
    public function testTheForgotPasswordPage(): void
    {
        $this->withoutMix();

        $this->get(route('canvas.password.request'))
             ->assertSuccessful()
             ->assertViewIs('canvas::auth.passwords.email')
             ->assertSeeText('Send Password Reset Link');
    }

    public function testForgotPasswordLinkRequestWillValidateAnInvalidEmail(): void
    {
        $response = $this->post(route('canvas.password.email'), [
            'email' => 'not-an-email',
        ]);

        $this->assertInstanceOf(ValidationException::class, $response->exception);
    }

    public function testThePasswordResetLinkCanBeSent(): void
    {
        Mail::fake();

        $this->post(route('canvas.password.email'), [
            'email' => $this->admin->email,
        ])
             ->assertRedirect(route('canvas.password.request'));

        Mail::assertSent(ResetPassword::class, function ($mail) {
            $this->assertIsString($mail->token);

            return $mail->hasTo($this->admin->email);
        });
    }
}
