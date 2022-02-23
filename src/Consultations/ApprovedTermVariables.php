<?php

namespace EscolaLms\TemplatesEmail\Consultations;

class ApprovedTermVariables extends CommonConsultationVariables
{
    const VAR_COURSE_DEADLINE = '@VarApprovedTerm';

    // TODO Add variable to emails
    public static function defaultSectionsContent(): array
    {
        return [
            'title' => '',
            'content' => ''
        ];
    }
}
