<?php

namespace EscolaLms\TemplatesEmail\Courses;

use EscolaLms\Courses\Models\Course;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\TemplatesEmail\Core\EmailVariables;

abstract class CommonUserAndCourseVariables extends EmailVariables
{
    const VAR_USER_NAME       = '@VarUserName';
    const VAR_COURSE_TITLE    = '@VarCourseTitle';

    public static function mockedVariables(): array
    {
        $faker = \Faker\Factory::create();
        return array_merge(parent::mockedVariables(), [
            self::VAR_USER_NAME       => $faker->name(),
            self::VAR_COURSE_TITLE    => $faker->word(),
        ]);
    }

    public static function variablesFromEvent(EventWrapper $event): array
    {
        return array_merge(parent::variablesFromEvent($event), [
            self::VAR_USER_NAME    => $event->getUser()->name,
            self::VAR_COURSE_TITLE => $event->getCourse()->title,
        ]);
    }

    public static function requiredVariables(): array
    {
        return [
            self::VAR_USER_NAME,
            self::VAR_COURSE_TITLE,
        ];
    }

    public static function requiredVariablesInSection(string $sectionKey): array
    {
        if ($sectionKey === 'content') {
            [
                self::VAR_USER_NAME,
                self::VAR_COURSE_TITLE,
            ];
        }
        return [];
    }

    public static function assignableClass(): ?string
    {
        return Course::class;
    }
}
