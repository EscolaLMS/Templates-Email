<?php

namespace EscolaLms\TemplatesEmail\Tests\Api;

use EscolaLms\Core\Tests\ApiTestTrait;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\CsvUsers\Events\EscolaLmsNewUserImportedTemplateEvent;
use EscolaLms\CsvUsers\Services\Contracts\CsvUserServiceContract;
use EscolaLms\Templates\Listeners\TemplateEventListener;
use EscolaLms\TemplatesEmail\Core\EmailMailable;
use EscolaLms\TemplatesEmail\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class CsvUsersTest extends TestCase
{
    use CreatesUsers, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        if (!class_exists(\EscolaLms\CsvUsers\EscolaLmsCsvUsersServiceProvider::class)) {
            $this->markTestSkipped('Auth package not installed');
        }
    }

    public function testImportNewUserNotification(): void
    {
        Notification::fake();
        Event::fake();
        Mail::fake();

        $userToImport = collect([
            'email' => 'import.user@poczta.com',
            'first_name' => 'Import',
            'last_name' => 'User',
        ]);

        $service = app(CsvUserServiceContract::class);
        $user = $service->saveUserFromImport($userToImport);

        Event::assertDispatched(EscolaLmsNewUserImportedTemplateEvent::class,
            function (EscolaLmsNewUserImportedTemplateEvent $event) use ($userToImport) {
                return $event->getUser() === $userToImport['email'];
            });

        $listener = app(TemplateEventListener::class);
        $listener->handle(new EscolaLmsNewUserImportedTemplateEvent($user));

        Mail::assertSent(EmailMailable::class, function (EmailMailable $mailable) use ($user) {
            $this->assertEquals('User Import Notification', $mailable->subject);
            $this->assertTrue($mailable->hasTo($user->email));
            $this->assertStringContainsString('token=' . $user->password_reset_token, $mailable->getHtml());
            $this->assertStringContainsString('email=' . $user->email, $mailable->getHtml());
            return true;
        });
    }
}
