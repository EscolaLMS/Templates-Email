<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\Auth\Events\EscolaLmsAccountRegisteredTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsForgotPasswordTemplateEvent;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesEmail\Auth\ResetPasswordVariables;
use EscolaLms\TemplatesEmail\Auth\VerifyEmailVariables;
use EscolaLms\TemplatesEmail\Core\EmailChannel;
use Illuminate\Support\ServiceProvider;

class AuthTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Template::register(EscolaLmsForgotPasswordTemplateEvent::class, EmailChannel::class, ResetPasswordVariables::class);
        Template::register(EscolaLmsAccountRegisteredTemplateEvent::class, EmailChannel::class, VerifyEmailVariables::class);
    }
}
