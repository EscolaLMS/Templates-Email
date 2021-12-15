<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\Auth\Events\PasswordForgotten;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesEmail\Auth\ResetPasswordVariables;
use EscolaLms\TemplatesEmail\Auth\VerifyEmailVariables;
use EscolaLms\TemplatesEmail\Core\EmailChannel;
use EscolaLms\TemplatesEmail\Events\Registered;
use Illuminate\Support\ServiceProvider;

class AuthTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Template::register(PasswordForgotten::class, EmailChannel::class, ResetPasswordVariables::class);
        Template::register(Registered::class, EmailChannel::class, VerifyEmailVariables::class);
    }
}
