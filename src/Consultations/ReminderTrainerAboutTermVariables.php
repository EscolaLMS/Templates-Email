<?php

namespace EscolaLms\TemplatesEmail\Consultations;

use EscolaLms\Templates\Events\EventWrapper;

class ReminderTrainerAboutTermVariables extends CommonConsultationVariables
{
    const VAR_CONSULTATION_USER_NAME = '@VarConsultationUserName';

    public static function requiredVariables(): array
    {
        return array_merge(parent::requiredVariables(), [
            self::VAR_CONSULTATION_USER_NAME,
        ]);
    }

    public static function requiredVariablesInSection(string $sectionKey): array
    {
        if ($sectionKey === 'content') {
            return array_merge(parent::requiredVariablesInSection($sectionKey), [
                self::VAR_CONSULTATION_USER_NAME,
            ]);
        }
        return [];
    }

    public static function variablesFromEvent(EventWrapper $event): array
    {
        return array_merge(parent::variablesFromEvent($event), [
            self::VAR_CONSULTATION_USER_NAME => $event->getConsultationTerm()->user->name,
        ]);
    }

    public static function defaultSectionsContent(): array
    {
        return [
            'title' => __('Remind term ":consultation"', [
                'consultation' => self::VAR_CONSULTATION_TITLE,
            ]),
            'content' => self::wrapWithMjml(__('<h1>Hello :user_name!</h1><p>I would like to remind you about the upcoming consultation :consultation with :consultation_user_name, which will take place :proposed_term.</p>', [
                'user_name' => self::VAR_USER_NAME,
                'consultation_user_name' => self::VAR_CONSULTATION_USER_NAME,
                'consultation' => self::VAR_CONSULTATION_TITLE,
                'proposed_term' => self::VAR_CONSULTATION_PROPOSED_TERM
            ]),),
        ];
    }
}
