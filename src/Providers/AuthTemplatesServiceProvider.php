<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\Auth\Events\EscolaLmsAccountConfirmedTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsAccountMustBeEnableByAdminTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsAccountRegisteredTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsForgotPasswordTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsLoginTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsPasswordChangedTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsResetPasswordTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsUserAddedToGroupTemplateEvent;
use EscolaLms\Auth\Events\EscolaLmsUserRemovedFromGroupTemplateEvent;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesEmail\Auth\AccountConfirmedVariables;
use EscolaLms\TemplatesEmail\Auth\PasswordChangedVariables;
use EscolaLms\TemplatesEmail\Auth\ResetPasswordVariables;
use EscolaLms\TemplatesEmail\Auth\UserAddedToGroupVariables;
use EscolaLms\TemplatesEmail\Auth\UserRemovedFromGroupVariables;
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
        Template::register(EscolaLmsAccountConfirmedTemplateEvent::class, EmailChannel::class, AccountConfirmedVariables::class);
        Template::register(EscolaLmsUserAddedToGroupTemplateEvent::class, EmailChannel::class, UserAddedToGroupVariables::class);
        Template::register(EscolaLmsUserRemovedFromGroupTemplateEvent::class, EmailChannel::class, UserRemovedFromGroupVariables::class);
        Template::register(EscolaLmsResetPasswordTemplateEvent::class, EmailChannel::class, ResetPasswordVariables::class);
        Template::register(EscolaLmsPasswordChangedTemplateEvent::class, EmailChannel::class, PasswordChangedVariables::class);
        Template::register(EscolaLmsLoginTemplateEvent::class, EmailChannel::class, VerifyEmailVariables::class);
    }
}
