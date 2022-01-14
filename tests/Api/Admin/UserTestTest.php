<?php

namespace EscolaLms\TemplatesEmail\Tests\Api\Admin;

use EscolaLms\Auth\Database\Seeders\AuthPermissionSeeder;
use EscolaLms\Auth\Events\EscolaLmsAccountBlockedTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsAccountDeletedTemplateEvent;
use EscolaLms\Core\Tests\ApiTestTrait;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Templates\Listeners\TemplateEventListener;
use EscolaLms\TemplatesEmail\Core\EmailMailable;
use EscolaLms\TemplatesEmail\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class UserTestTest extends TestCase
{
    use CreatesUsers, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        if (!class_exists(\EscolaLms\Auth\EscolaLmsAuthServiceProvider::class)) {
            $this->markTestSkipped('Auth package not installed');
        }
    }

    public function testAccountDeletedNotification(): void
    {
        $this->seed(AuthPermissionSeeder::class);
        Mail::fake();
        Event::fake([EscolaLmsAccountDeletedTemplateEvent::class]);
        Notification::fake();

        $admin = $this->makeAdmin();
        $student = $this->makeStudent();

        $this->response = $this->actingAs($admin, 'api')->deleteJson("/api/admin/users/{$student->getKey()}");

        $this->assertApiSuccess();
        $this->assertDatabaseMissing('users', [
            'email' => $student->email,
        ]);

        Event::assertDispatched(EscolaLmsAccountDeletedTemplateEvent::class);

        $listener = app(TemplateEventListener::class);
        $listener->handle(new EscolaLmsAccountDeletedTemplateEvent($student));

        Mail::assertSent(EmailMailable::class, function (EmailMailable $mailable) use ($student) {
            $this->assertEquals('Account Deleted Notification', $mailable->subject);
            $this->assertTrue($mailable->hasTo($student->email));
            return true;
        });
    }

    public function testAccountBlockedNotification(): void
    {
        $this->seed(AuthPermissionSeeder::class);
        Mail::fake();
        Notification::fake();
        Event::fake([EscolaLmsAccountBlockedTemplateEvent::class]);

        $user = $this->makeStudent([
            'is_active' => false,
        ]);

        $admin = $this->makeAdmin();

        $this->response = $this->actingAs($admin)->json('PUT', '/api/admin/users/' . $user->getKey(), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'is_active' => true,
        ]);

        Event::assertNotDispatched(EscolaLmsAccountBlockedTemplateEvent::class);
        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'is_active' => true,
        ]);

        $this->response = $this->actingAs($admin)->json('PUT', '/api/admin/users/' . $user->getKey(), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'is_active' => false,
        ]);

        Event::assertDispatched(EscolaLmsAccountBlockedTemplateEvent::class);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'is_active' => false,
        ]);

        $listener = app(TemplateEventListener::class);
        $listener->handle(new EscolaLmsAccountBlockedTemplateEvent($user));

        Mail::assertSent(EmailMailable::class, function (EmailMailable $mailable) use ($user) {
            $this->assertEquals('Account Blocked Notification', $mailable->subject);
            $this->assertTrue($mailable->hasTo($user->email));
            return true;
        });
    }
}
