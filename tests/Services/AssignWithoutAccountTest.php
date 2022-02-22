<?php

namespace EscolaLms\TemplatesEmail\Tests\Services;

use EscolaLms\AssignWithoutAccount\Events\UserSubmissionAccepted;
use EscolaLms\AssignWithoutAccount\Models\AccessUrl;
use EscolaLms\AssignWithoutAccount\Models\UserSubmission;
use EscolaLms\AssignWithoutAccount\Services\Contracts\UserSubmissionServiceContract;
use EscolaLms\AssignWithoutAccount\Services\UserSubmissionService;
use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Listeners\TemplateEventListener;
use EscolaLms\TemplatesEmail\Core\EmailMailable;
use EscolaLms\TemplatesEmail\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class AssignWithoutAccountTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        if (!class_exists(\EscolaLms\AssignWithoutAccount\EscolaLmsAssignWithoutAccountServiceProvider::class)) {
            $this->markTestSkipped('Assign-Without-Account package not installed');
        }
    }

    public function testUserSubmissionAccepted(): void
    {
        Notification::fake();
        Event::fake();
        Mail::fake();

        $accessUrl = AccessUrl::factory()->create();
        $userSubmission = UserSubmission::factory()->create([
            'access_url_id' => $accessUrl->getKey()
        ]);

        $service = app(UserSubmissionService::class);
        $service->accept($userSubmission->getKey());

        Event::assertDispatched(UserSubmissionAccepted::class,
            function (UserSubmissionAccepted $event) use ($userSubmission) {
                return $event->getUser()->email === $userSubmission->email && $event->getUrl() === $userSubmission->frontend_url;
            });

        $user = new User();
        $user->email = $userSubmission->email;

        $listener = app(TemplateEventListener::class);
        $listener->handle(new UserSubmissionAccepted($user, $userSubmission->frontend_url));

        Mail::assertSent(EmailMailable::class, function (EmailMailable $mailable) use ($userSubmission) {
            $this->assertEquals('Access granted', $mailable->subject);
            $this->assertTrue($mailable->hasTo($userSubmission->email));
            $this->assertStringContainsString($userSubmission->frontend_url, $mailable->getHtml());
            return true;
        });
    }
}
