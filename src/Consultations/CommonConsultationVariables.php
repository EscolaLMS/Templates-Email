<?php

namespace EscolaLms\TemplatesEmail\Consultations;

use EscolaLms\Consultations\Models\ConsultationUserPivot;
use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\TemplatesEmail\Core\EmailVariables;

abstract class CommonConsultationVariables extends EmailVariables
{
    const VAR_USER_NAME       = '@VarUserName';
    const VAR_CONSULTATION_TITLE    = '@VarConsultationTitle';
    const VAR_CONSULTATION_PROPOSED_TERM    = '@VarConsultationProposedTerm';

    public static function mockedVariables(?User $user = null): array
    {
        $faker = \Faker\Factory::create();
        return array_merge(parent::mockedVariables(), [
            self::VAR_USER_NAME       => $faker->name(),
            self::VAR_CONSULTATION_TITLE    => $faker->word(),
            self::VAR_CONSULTATION_PROPOSED_TERM => $faker->dateTime(),
        ]);
    }

    public static function variablesFromEvent(EventWrapper $event): array
    {
        return array_merge(parent::variablesFromEvent($event), [
            self::VAR_USER_NAME    => $event->getUser()->name,
            self::VAR_CONSULTATION_TITLE => $event->getConsultationTerm()->consultation->name,
            self::VAR_CONSULTATION_PROPOSED_TERM => $event->getConsultationTerm()->executed_at,
        ]);
    }

    public static function requiredVariables(): array
    {
        return [
            self::VAR_USER_NAME,
            self::VAR_CONSULTATION_TITLE,
            self::VAR_CONSULTATION_PROPOSED_TERM,
        ];
    }

    public static function requiredVariablesInSection(string $sectionKey): array
    {
        if ($sectionKey === 'content') {
            return [
                self::VAR_USER_NAME,
                self::VAR_CONSULTATION_TITLE,
                self::VAR_CONSULTATION_PROPOSED_TERM,
            ];
        }
        return [];
    }

    public static function assignableClass(): ?string
    {
        return ConsultationUserPivot::class;
    }
}
