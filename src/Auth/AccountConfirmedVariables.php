<?php

namespace EscolaLms\TemplatesEmail\Auth;

use EscolaLms\Templates\Events\EventWrapper;

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
