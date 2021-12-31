<?php

namespace EscolaLms\TemplatesEmail\Tests\Api;

use EscolaLms\Auth\EscolaLmsAuthServiceProvider;
use EscolaLms\Auth\Events\EscolaLmsAccountMustBeEnableByAdminTemplateEvent;
use EscolaLms\Auth\Events\PasswordForgotten;
use EscolaLms\Core\Models\User;
use EscolaLms\Core\Tests\ApiTestTrait;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Templates\Listeners\TemplateEventListener;
use EscolaLms\TemplatesEmail\Core\EmailMailable;
use EscolaLms\TemplatesEmail\Events\Registered;
use EscolaLms\TemplatesEmail\Notifications\ResetPassword;
use EscolaLms\TemplatesEmail\Tests\TestCase;
use Illuminate\Auth\Notifications\ResetPassword as LaravelResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class AuthTest extends TestCase
{
    use CreatesUsers, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        if (!class_exists(\EscolaLms\Auth\EscolaLmsAuthServiceProvider::class)) {
            $this->markTestSkipped('Auth package not installed');
        }
    }

    public function testVerifyEmail()
    {
        Mail::fake();
        Event::fake([Registered::class]);
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

        Event::assertDispatched(Registered::class);
        Notification::assertNotSentTo($user, VerifyEmail::class);

        $listener = app(TemplateEventListener::class);
        $listener->handle(new Registered($user));

        Mail::assertSent(EmailMailable::class, function (EmailMailable $mailable) use ($user) {
            $this->assertEquals('Verify Email Address', $mailable->subject);
            $this->assertTrue($mailable->hasTo($user->email));
            return true;
        });
    }

    public function testResetPassword()
    {
        Mail::fake();
        Event::fake();
        Notification::fake();

        $user = $this->makeStudent();

        $this->response = $this->json('POST', '/api/auth/password/forgot', [
            'email' => $user->email,
            'return_url' => 'http://localhost/password-forgot',
        ]);

        $this->assertApiSuccess();

        Event::assertDispatched(PasswordForgotten::class);
        Notification::assertNotSentTo($user, ResetPassword::class);
        Notification::assertNotSentTo($user, LaravelResetPassword::class);

        $event = new PasswordForgotten($user, 'http://localhost/password-forgot');
        $listener = app(TemplateEventListener::class);
        $listener->handle($event);

        Mail::assertSent(EmailMailable::class, function (EmailMailable $mailable) use ($user) {
            $this->assertEquals('Reset Password Notification', $mailable->subject);
            $this->assertTrue($mailable->hasTo($user->email));
            $this->assertStringContainsString('http://localhost/password-forgot', $mailable->getHtml());
            $this->assertStringContainsString('token=' . $user->password_reset_token, $mailable->getHtml());
            $this->assertStringContainsString('email=' . $user->email, $mailable->getHtml());
            return true;
        });
    }

    public function testAccountMustBeEnableByAdmin(): void
    {
        Mail::fake();
        Event::fake([EscolaLmsAccountMustBeEnableByAdminTemplateEvent::class]);
        Notification::fake();
        Config::set(EscolaLmsAuthServiceProvider::CONFIG_KEY  . '.account_must_be_enabled_by_admin', true);

        $admin = config('auth.providers.users.model')::factory()->create();
        $admin->guard_name = 'api';
        $admin->assignRole('admin');

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

        $newUser = User::where('email', 'test@test.test')->first();

        Event::assertDispatched(EscolaLmsAccountMustBeEnableByAdminTemplateEvent::class);
        Notification::assertNotSentTo($newUser, VerifyEmail::class);

        $listener = app(TemplateEventListener::class);
        $listener->handle(new EscolaLmsAccountMustBeEnableByAdminTemplateEvent($admin, $newUser));

        Mail::assertSent(EmailMailable::class, function (EmailMailable $mailable) use ($admin, $newUser) {
            $this->assertEquals('Verify User account', $mailable->subject);
            $this->assertTrue($mailable->hasTo($admin->email));
            $this->assertFalse($mailable->hasTo($newUser->email));

            return true;
        });
    }
}
