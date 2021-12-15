<?php

namespace EscolaLms\TemplatesEmail\Auth;

use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\TemplatesEmail\Core\EmailVariables;

abstract class CommonAuthVariables extends EmailVariables
{
    const VAR_USER_EMAIL      = "@VarUserEmail";
    const VAR_USER_FIRST_NAME = "@VarStudentFirstName";
    const VAR_USER_LAST_NAME  = "@VarStudentLastName";
    const VAR_USER_FULL_NAME  = "@VarStudentFullName";
    const VAR_ACTION_LINK     = "@VarActionLink";

    public static function mockedVariables(): array
    {
        $faker = \Faker\Factory::create();
        return array_merge(parent::mockedVariables(), [
            self::VAR_USER_EMAIL => $faker->email,
            self::VAR_USER_FIRST_NAME => $faker->firstName,
            self::VAR_USER_LAST_NAME => $faker->lastName,
            self::VAR_USER_FULL_NAME => $faker->name,
            self::VAR_ACTION_LINK => url('/'),
        ]);
    }

    public static function variablesFromEvent(EventWrapper $event): array
    {
        $user = $event->getUser();
        return [
            self::VAR_USER_EMAIL => $user->email,
            self::VAR_USER_FIRST_NAME => $user->firstName,
            self::VAR_USER_LAST_NAME => $user->lastName,
            self::VAR_USER_FULL_NAME => $user->name,
            self::VAR_ACTION_LINK => static::getActionLink($event),
        ];
    }

    abstract static function getActionLink(EventWrapper $event): string;

    public static function requiredVariables(): array
    {
        return [
            self::VAR_ACTION_LINK,
        ];
    }

    public static function requiredVariablesInSection(string $sectionKey): array
    {
        if ($sectionKey === 'content') {
            return [
                self::VAR_ACTION_LINK
            ];
        }
        return [];
    }

    public static function assignableClass(): ?string
    {
        return null;
    }
}
