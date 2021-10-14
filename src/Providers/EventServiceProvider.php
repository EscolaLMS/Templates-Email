<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\Auth\Events\PasswordForgotten;
use EscolaLms\TemplatesEmail\Listeners\CreatePasswordResetToken;
use EscolaLms\TemplatesEmail\Listeners\SendEmailVerificationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends \Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PasswordForgotten::class => [
            CreatePasswordResetToken::class,
        ],
    ];

    public function register()
    {
        $this->booted(function () {
            Event::forget(PasswordForgotten::class);

            Event::forget(Registered::class);

            $events = $this->getEvents();

            foreach ($events as $event => $listeners) {
                foreach (array_unique($listeners) as $listener) {
                    Event::listen($event, $listener);
                }
            }

            foreach ($this->subscribe as $subscriber) {
                Event::subscribe($subscriber);
            }
        });
        parent::register();
    }
}
