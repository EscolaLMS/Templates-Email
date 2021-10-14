<?php

namespace EscolaLms\TemplatesEmail\Tests\Api;

use EscolaLms\Auth\Events\PasswordForgotten;
use EscolaLms\Auth\Models\User;
use EscolaLms\Auth\Notifications\ResetPassword as AuthResetPassword;
use EscolaLms\Core\Tests\ApiTestTrait;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\TemplatesEmail\Enums\Email\ResetPasswordVariables;
use EscolaLms\TemplatesEmail\Enums\Email\VerifyEmailVariables;
use EscolaLms\TemplatesEmail\Listeners\CreatePasswordResetToken;
use EscolaLms\TemplatesEmail\Models\Template;
use EscolaLms\TemplatesEmail\Notifications\ResetPassword;
use EscolaLms\TemplatesEmail\Notifications\VerifyEmail;
use EscolaLms\TemplatesEmail\Tests\TestCase;
use Illuminate\Auth\Notifications\VerifyEmail as LaravelVerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use InvalidArgumentException;

class AuthTest extends TestCase
{
    use CreatesUsers, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    public function testVerifyEmail()
    {
        $template = Template::factory()->create([
            'type' => VerifyEmailVariables::getType(),
            'vars_set' => VerifyEmailVariables::getSubtype(),
            'is_default' => true,
            'content' => "ASDF" . PHP_EOL . VerifyEmailVariables::USER_EMAIL . PHP_EOL . VerifyEmailVariables::ACTION_LINK,
        ]);

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
        var_dump($mail->toArray());
    }

    public function testResetPassword()
    {
        $template = Template::factory()->create([
            'type' => ResetPasswordVariables::getType(),
            'vars_set' => ResetPasswordVariables::getSubtype(),
            'is_default' => true,
            'content' => "ASDF" . PHP_EOL . ResetPasswordVariables::USER_EMAIL . PHP_EOL . ResetPasswordVariables::ACTION_LINK,
        ]);

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
        var_dump($mail->toArray());
    }
}
