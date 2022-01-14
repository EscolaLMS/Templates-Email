<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\Auth\Events\EscolaLmsAccountBlockedTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsAccountDeletedTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsAccountMustBeEnableByAdminTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsAccountRegisteredTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsForgotPasswordTemplateEvent;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesEmail\Auth\AccountBlockedVariables;
use EscolaLms\TemplatesEmail\Auth\AccountDeletedVariables;
use EscolaLms\TemplatesEmail\Auth\ResetPasswordVariables;
use EscolaLms\TemplatesEmail\Auth\VerifyEmailVariables;
use EscolaLms\TemplatesEmail\Auth\VerifyUserAccountVariables;
use EscolaLms\TemplatesEmail\Core\EmailChannel;
use Illuminate\Support\ServiceProvider;

class AuthTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Template::register(EscolaLmsAccountMustBeEnableByAdminTemplateEvent::class, EmailChannel::class, VerifyUserAccountVariables::class);
        Template::register(EscolaLmsForgotPasswordTemplateEvent::class, EmailChannel::class, ResetPasswordVariables::class);
        Template::register(EscolaLmsAccountRegisteredTemplateEvent::class, EmailChannel::class, VerifyEmailVariables::class);
        Template::register(EscolaLmsAccountDeletedTemplateEvent::class, EmailChannel::class, AccountDeletedVariables::class);
        Template::register(EscolaLmsAccountBlockedTemplateEvent::class, EmailChannel::class, AccountBlockedVariables::class);
    }
}
