<?php

namespace EscolaLms\TemplatesEmail\Listeners;

use EscolaLms\TemplatesEmail\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification as LaravelSendEmailVerificationNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class SendEmailVerificationNotification extends LaravelSendEmailVerificationNotification
{
    public function handle(Registered $event)
    {
        /** @var \EscolaLms\Auth\Models\User $user */
        $user = $event->user;
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            $user->notify(new VerifyEmail);
        }
    }
}
