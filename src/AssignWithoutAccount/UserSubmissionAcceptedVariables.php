<?php

namespace EscolaLms\TemplatesEmail\AssignWithoutAccount;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\TemplatesEmail\Core\EmailVariables;
use Illuminate\Support\Facades\Lang;

class UserSubmissionAcceptedVariables extends EmailVariables
{
    const VAR_URL = "@VarUrl";

    public static function mockedVariables(?User $user = null): array
    {
        return array_merge(parent::mockedVariables($user), [
            self::VAR_URL => '',
        ]);
    }

    public static function variablesFromEvent(EventWrapper $event): array
    {
        return array_merge(parent::variablesFromEvent($event), [
            self::VAR_URL => $event->getUrl(),
        ]);
    }

    public static function defaultSectionsContent(): array
    {
        return [
            'title' => Lang::get('Access granted'),
            'content' => self::wrapWithMjml(
                '<mj-text>'
                . '<p>'
                . Lang::get('You are receiving this email because access has been granted to you.')
                . '</p>'
                . '</mj-text>'
                . '<mj-button href="' . self::VAR_URL . '">' . Lang::get('Join') . '</mj-button>'
            )
        ];
    }

    public static function assignableClass(): ?string
    {
        return null;
    }

    public static function requiredVariables(): array
    {
        return [
            self::VAR_URL
        ];
    }

    public static function requiredVariablesInSection(string $sectionKey): array
    {
        if ($sectionKey === 'content') {
            return [
                self::VAR_URL,
            ];
        }

        return [];
    }
}
