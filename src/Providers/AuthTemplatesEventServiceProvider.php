<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\Auth\Events\PasswordForgotten;
use EscolaLms\TemplatesEmail\Events\Registered as NamespacedRegisteredEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

class AuthTemplatesEventServiceProvider extends \Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    public function register()
    {
        $this->booted(function () {
            Event::forget(PasswordForgotten::class);
            Event::forget(Registered::class);
            Event::listen(Registered::class, function (Registered $event) {
                event(new NamespacedRegisteredEvent($event->user));
            });
        });
        parent::register();
    }
}
