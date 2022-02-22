<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\AssignWithoutAccount\Events\UserSubmissionAccepted;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesEmail\AssignWithoutAccount\UserSubmissionAcceptedVariables;
use EscolaLms\TemplatesEmail\Core\EmailChannel;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class AssignWithoutAccountTemplatesEventServiceProvider extends EventServiceProvider
{
    public function boot()
    {
        Template::register(
            UserSubmissionAccepted::class,
            EmailChannel::class,
            UserSubmissionAcceptedVariables::class
        );
    }
}
