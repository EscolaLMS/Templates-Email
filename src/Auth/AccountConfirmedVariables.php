<?php

namespace EscolaLms\TemplatesEmail\Auth;

use EscolaLms\Templates\Events\EventWrapper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class AccountConfirmedVariables extends CommonAuthVariables
{
    // TODO
    static function getActionLink(EventWrapper $event): string
    {
        return '';
    }

    public static function defaultSectionsContent(): array
    {
        return [];
    }
}
