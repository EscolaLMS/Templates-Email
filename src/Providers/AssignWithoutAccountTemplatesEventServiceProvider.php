<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\AssignWithoutAccount\Events\AssignToProduct;
use EscolaLms\AssignWithoutAccount\Events\AssignToProductable;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesEmail\AssignWithoutAccount\AssignToProductableVariables;
use EscolaLms\TemplatesEmail\AssignWithoutAccount\AssignToProductVariables;
use EscolaLms\TemplatesEmail\Core\EmailChannel;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class AssignWithoutAccountTemplatesEventServiceProvider extends EventServiceProvider
{
    public function boot()
    {
        Template::register(
            AssignToProduct::class,
            EmailChannel::class,
            AssignToProductVariables::class
        );

        Template::register(
            AssignToProductable::class,
            EmailChannel::class,
            AssignToProductableVariables::class
        );
    }
}
