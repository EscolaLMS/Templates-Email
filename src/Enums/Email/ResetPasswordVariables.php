<?php

namespace EscolaLms\TemplatesEmail\Enums\Email;

use EscolaLms\Auth\Models\User;

class ResetPasswordVariables extends AbstractAuthEmailVariables
{
    const VARSET = 'reset-password';

    const VAR_ACTION_LINK_EXPIRATION = "@VarActionLinkExpiration";

    public static function getMockVariables(): array
    {
        return array_merge(parent::getMockVariables(), [
            self::VAR_ACTION_LINK_EXPIRATION => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire'),
        ]);
    }

    public static function getVariablesFromContent(?User $user = null, ?string $action_link = null): array
    {
        return array_merge(parent::getVariablesFromContent($user, $action_link), [
            self::VAR_ACTION_LINK_EXPIRATION => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire'),
        ]);
    }

    public static function getVarSet(): string
    {
        return self::VARSET;
    }
}
