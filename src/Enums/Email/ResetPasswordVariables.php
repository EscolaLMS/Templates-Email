<?php

namespace EscolaLms\TemplatesEmail\Enums\Email;

use EscolaLms\Auth\Models\User;

class ResetPasswordVariables extends AbstractAuthEmailVariables
{
    const SUBTYPE = 'reset-password';

    const ACTION_LINK_EXPIRATION = "@VarActionLinkExpiration";

    public static function getMockVariables(): array
    {
        return array_merge(parent::getMockVariables(), [
            self::ACTION_LINK_EXPIRATION => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire'),
        ]);
    }

    public static function getVariablesFromContent(?User $user = null, ?string $action_link = null): array
    {
        return array_merge(parent::getVariablesFromContent($user, $action_link), [
            self::ACTION_LINK_EXPIRATION => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire'),
        ]);
    }

    public static function getSubtype(): string
    {
        return self::SUBTYPE;
    }
}
