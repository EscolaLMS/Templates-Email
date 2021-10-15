<?php

namespace EscolaLms\TemplatesEmail\Enums\Email;

class VerifyEmailVariables extends AbstractAuthEmailVariables
{
    const VARSET = 'verify-email';

    public static function getVarSet(): string
    {
        return self::VARSET;
    }
}
