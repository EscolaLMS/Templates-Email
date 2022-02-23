<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\Consultations\Events\ApprovedTerm;
use EscolaLms\Consultations\Events\RejectTerm;
use EscolaLms\Consultations\Events\ReportTerm;
use EscolaLms\TemplatesEmail\Consultations\ApprovedTermVariables;
use EscolaLms\TemplatesEmail\Consultations\RejectTermVariables;
use EscolaLms\TemplatesEmail\Consultations\ReportTermVariables;
use EscolaLms\TemplatesEmail\Core\EmailChannel;
use Illuminate\Support\ServiceProvider;
use EscolaLms\Templates\Facades\Template;

class ConsultationTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Template::register(ApprovedTerm::class, EmailChannel::class, ApprovedTermVariables::class);
        Template::register(RejectTerm::class, EmailChannel::class, RejectTermVariables::class);
        Template::register(ReportTerm::class, EmailChannel::class, ReportTermVariables::class);
    }
}
