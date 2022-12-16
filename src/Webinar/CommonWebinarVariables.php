<?php

namespace EscolaLms\TemplatesEmail\Webinar;

use Carbon\Carbon;
use DateTimeInterface;
use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\TemplatesEmail\Core\EmailVariables;
use EscolaLms\Webinar\Models\Webinar;

abstract class CommonWebinarVariables extends EmailVariables
{
    const VAR_USER_NAME       = '@VarUserName';
    const VAR_WEBINAR_TITLE    = '@VarWebinarTitle';
    const VAR_WEBINAR_PROPOSED_TERM    = '@VarWebinarProposedTerm';

    public static function mockedVariables(?User $user = null): array
    {
        $faker = \Faker\Factory::create();
        return array_merge(parent::mockedVariables(), [
            self::VAR_USER_NAME       => $faker->name(),
            self::VAR_WEBINAR_TITLE    => $faker->word(),
            self::VAR_WEBINAR_PROPOSED_TERM => $faker->dateTime()->format('Y-m-d H:i:s'),
        ]);
    }

    public static function variablesFromEvent(EventWrapper $event): array
    {
        return array_merge(parent::variablesFromEvent($event), [
            self::VAR_USER_NAME    => $event->getUser()->name,
            self::VAR_WEBINAR_TITLE => $event->getWebinar()->name,
            self::VAR_WEBINAR_PROPOSED_TERM => $proposedTerm ?
                $proposedTerm
                ->setTimezone($event->getUser()->current_timezone)
                ->format('Y-m-d H:i:s') :
                '',
        ]);
    }

    public static function requiredVariables(): array
    {
        return [
            self::VAR_USER_NAME,
            self::VAR_WEBINAR_TITLE,
        ];
    }

    public static function requiredVariablesInSection(string $sectionKey): array
    {
        if ($sectionKey === 'content') {
            return [
                self::VAR_USER_NAME,
                self::VAR_WEBINAR_TITLE,
            ];
        }
        return [];
    }

    public static function assignableClass(): ?string
    {
        return Webinar::class;
    }
}
