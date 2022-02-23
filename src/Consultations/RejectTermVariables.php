<?php

namespace EscolaLms\TemplatesEmail\Consultations;

class RejectTermVariables extends CommonConsultationVariables
{
    const VAR_COURSE_DEADLINE = '@VarRejectTerm';

    // TODO Add variable to emails
    public static function defaultSectionsContent(): array
    {
        return [
            'title' => '',
            'content' => ''
        ];
    }
}
