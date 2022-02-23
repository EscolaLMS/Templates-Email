<?php

namespace EscolaLms\TemplatesEmail\Consultations;

class ReportTermVariables extends CommonConsultationVariables
{
    const VAR_COURSE_DEADLINE = '@VarReportTerm';

    // TODO Add variable to emails
    public static function defaultSectionsContent(): array
    {
        return [
            'title' => '',
            'content' => ''
        ];
    }
}
