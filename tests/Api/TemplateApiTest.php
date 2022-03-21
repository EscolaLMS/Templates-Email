<?php

namespace EscolaLms\TemplatesEmail\Tests\Api;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Templates\Events\ManuallyTriggeredEvent;
use EscolaLms\Templates\Listeners\TemplateEventListener;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Models\TemplateSection;
use EscolaLms\TemplatesEmail\Core\EmailMailable;
use EscolaLms\TemplatesEmail\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

class TemplateApiTest extends TestCase
{
    use DatabaseTransactions, CreatesUsers;

    public function testManuallyTriggeredEvent(): void
    {
        Event::fake(ManuallyTriggeredEvent::class);
        Mail::fake();

        $admin = $this->makeAdmin();
        $student = $this->makeStudent();

        $this->response = $this->actingAs($admin, 'api')->postJson(
            '/api/admin/events/trigger-manually',
            ['users' => [$student->getKey()]]
        )->assertOk();

        Event::assertDispatched(ManuallyTriggeredEvent::class, function (ManuallyTriggeredEvent $event) use ($student) {
            $this->assertEquals($student->getKey(), $event->getUser()->getKey());
            return true;
        });

        $listener = app(TemplateEventListener::class);
        $listener->handle(new ManuallyTriggeredEvent($student));

        Mail::assertSent(EmailMailable::class, function (EmailMailable $mailable) use ($student) {
            $this->assertTrue($mailable->hasTo($student->email));
            return true;
        });
    }
}
