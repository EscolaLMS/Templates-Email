<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\Tasks\Events\TaskAssignedEvent;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesEmail\Core\EmailChannel;
use EscolaLms\TemplatesEmail\Tasks\TaskAssignedVariables;
use Illuminate\Support\ServiceProvider;

class TaskTemplatesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Template::register(
            TaskAssignedEvent::class,
            EmailChannel::class,
            TaskAssignedVariables::class
        );
    }
}
