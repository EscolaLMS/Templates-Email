<?php

namespace EscolaLms\TemplatesEmail\Tests\Api;

use EscolaLms\Core\Models\User;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Templates\Database\Seeders\PermissionTableSeeder;
use EscolaLms\Templates\Listeners\TemplateEventListener;
use EscolaLms\Templates\Models\Template;
use EscolaLms\TemplatesEmail\Core\EmailChannel;
use EscolaLms\TemplatesEmail\Core\EmailMailable;
use EscolaLms\TemplatesEmail\Tests\TestCase;
use EscolaLms\Youtube\Dto\YTBroadcastDto;
use EscolaLms\Youtube\EscolaLmsYoutubeServiceProvider;
use EscolaLms\Youtube\Events\YtProblem;
use EscolaLms\Youtube\Services\Contracts\YoutubeServiceContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class YoutubeTest extends TestCase
{
    use DatabaseTransactions, CreatesUsers, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        if (!class_exists(EscolaLmsYoutubeServiceProvider::class)) {
            $this->markTestSkipped('Youtube package not installed');
        }
        $this->seed(PermissionTableSeeder::class);
    }

    public function testVerifyEmailAfterWrongYt()
    {
        Event::fake();
        Mail::fake();

        $email = $this->faker->email;
        Config::set('services.youtube.email', $email);
        $ytServiceContract = app(YoutubeServiceContract::class);
        try {
            $ytServiceContract->generateYTStream(new YTBroadcastDto());
        } catch (\Exception $ex) {
            //
        }
        $user = new User([
            'email' => $email
        ]);
        Event::assertDispatched(YtProblem::class, function (YtProblem $event) use ($user) {
            return $event->getUser()->email === $user->email;
        });
        $listener = app(TemplateEventListener::class);
        $listener->handle(new YtProblem($user));

        Mail::assertSent(EmailMailable::class, function (EmailMailable $mailable) use ($user) {
            $this->assertEquals(__('Problem with Yt integration'), $mailable->subject);
            $this->assertTrue($mailable->hasTo($user->email));
            return true;
        });
    }
}
