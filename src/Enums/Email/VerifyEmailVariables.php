<?php

namespace EscolaLms\TemplatesEmail\Enums\Email;

class VerifyEmailVariables extends AbstractAuthEmailVariables
{
    const SUBTYPE = 'verify-email';

    public static function getSubtype(): string
    {
        return self::SUBTYPE;
    }
}
