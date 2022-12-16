<?php

namespace EscolaLms\TemplatesEmail\Consultations;

use DateTimeInterface;
use EscolaLms\Consultations\Models\ConsultationUserPivot;
use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\TemplatesEmail\Core\EmailVariables;
use Illuminate\Support\Carbon;

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
        if ($event->getConsultationTerm()->executed_at instanceof DateTimeInterface) {
            $proposedTerm = $event->getConsultationTerm()->executed_at;
        } else {
            $proposedTerm = Carbon::make($event->getConsultationTerm()->executed_at);
        }

        return array_merge(parent::variablesFromEvent($event), [
            self::VAR_USER_NAME    => $event->getUser()->name,
            self::VAR_CONSULTATION_TITLE => $event->getConsultationTerm()->consultation->name,
            self::VAR_CONSULTATION_PROPOSED_TERM => $proposedTerm ?
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
