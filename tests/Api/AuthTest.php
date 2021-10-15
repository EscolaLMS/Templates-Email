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

class AuthTest extends TestCase
{
    use CreatesUsers, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    public function testVerifyEmail()
    {
        $template = Template::factory()->create([
            'type' => VerifyEmailVariables::getType(),
            'vars_set' => VerifyEmailVariables::getVarSet(),
            'is_default' => true,
            'content' => "ASDF" . PHP_EOL . VerifyEmailVariables::VAR_USER_EMAIL . PHP_EOL . VerifyEmailVariables::VAR_ACTION_LINK,
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
    }

    public function testResetPassword()
    {
        $template = Template::factory()->create([
            'type' => ResetPasswordVariables::getType(),
            'vars_set' => ResetPasswordVariables::getVarSet(),
            'is_default' => true,
            'content' => "ASDF" . PHP_EOL . ResetPasswordVariables::VAR_USER_EMAIL . PHP_EOL . ResetPasswordVariables::VAR_ACTION_LINK,
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
    }
}
