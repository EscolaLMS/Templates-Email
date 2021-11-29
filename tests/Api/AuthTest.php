<?php

namespace EscolaLms\TemplatesEmail\Tests\Api;

use EscolaLms\Auth\Events\PasswordForgotten;
use EscolaLms\Auth\Models\User;
use EscolaLms\Auth\Notifications\ResetPassword as AuthResetPassword;
use EscolaLms\Core\Tests\ApiTestTrait;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\TemplatesEmail\Listeners\CreatePasswordResetToken;
use EscolaLms\TemplatesEmail\Notifications\ResetPassword;
use EscolaLms\TemplatesEmail\Notifications\VerifyEmail;
use EscolaLms\TemplatesEmail\Tests\TestCase;
use Illuminate\Auth\Notifications\VerifyEmail as LaravelVerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

class AuthTest extends TestCase
{
    use CreatesUsers, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    public function testVerifyEmail()
    {
        Notification::fake();

        $this->response = $this->json('POST', '/api/auth/register', [
            'email' => 'test@test.test',
            'first_name' => 'tester',
            'last_name' => 'tester',
            'password' => 'testtest',
            'password_confirmation' => 'testtest',
        ]);

        $this->assertApiSuccess();
        $this->assertDatabaseHas('users', [
            'email' => 'test@test.test',
            'first_name' => 'tester',
            'last_name' => 'tester',
        ]);

        $user = User::where('email', 'test@test.test')->first();

        Notification::assertNotSentTo($user, LaravelVerifyEmail::class);
        Notification::assertSentTo($user, VerifyEmail::class);

        $notification = new VerifyEmail();
        $mail = $notification->toMail($user);
        $this->assertTrue($mail instanceof \Illuminate\Notifications\Messages\MailMessage);
    }

    public function testResetPassword()
    {
        Event::fake();
        Notification::fake();

        $user = $this->makeStudent();

        $this->response = $this->json('POST', '/api/auth/password/forgot', [
            'email' => $user->email,
            'return_url' => 'http://localhost/password-forgot',
        ]);

        $this->assertApiSuccess();
        Event::assertDispatched(PasswordForgotten::class);

        $event = new PasswordForgotten($user, 'http://localhost/password-forgot');
        $listener = app(CreatePasswordResetToken::class);
        $listener->handle($event);

        Notification::assertNotSentTo($user, AuthResetPassword::class);
        Notification::assertSentTo($user, ResetPassword::class);

        $notification = new ResetPassword($user->password_reset_token, $event->getReturnUrl());
        $mail = $notification->toMail($user);
        $this->assertTrue($mail instanceof \Illuminate\Notifications\Messages\MailMessage);
    }
}
