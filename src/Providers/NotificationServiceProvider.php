<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\Notifications\Facades\EscolaLmsNotifications;
use EscolaLms\TemplatesEmail\Notifications\ResetPassword;
use EscolaLms\TemplatesEmail\Notifications\VerifyEmail;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{

    public function boot()
    {
        EscolaLmsNotifications::registerNotification(ResetPassword::class);
        EscolaLmsNotifications::registerNotification(VerifyEmail::class);
    }
}
