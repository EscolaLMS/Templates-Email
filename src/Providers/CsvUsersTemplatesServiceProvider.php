<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\CsvUsers\Events\EscolaLmsNewUserImportedTemplateEvent;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesEmail\Core\EmailChannel;
use EscolaLms\TemplatesEmail\CsvUsers\NewUserImportedVariables;
use Illuminate\Support\ServiceProvider;

class CsvUsersTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Template::register(EscolaLmsNewUserImportedTemplateEvent::class, EmailChannel::class, NewUserImportedVariables::class);
    }
}
