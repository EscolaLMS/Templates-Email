<?php

namespace EscolaLms\TemplatesEmail\Enums\Email;

use EscolaLms\Auth\Models\User;
use EscolaLms\Core\Enums\BasicEnum;
use EscolaLms\Templates\Enum\Contracts\TemplateVariableContract;

abstract class AbstractAuthEmailVariables extends BasicEnum implements TemplateVariableContract
{
    //
    const TYPE = 'email';

    //
    const USER_EMAIL             = "@VarUserEmail";
    const USER_FIRST_NAME        = "@VarStudentFirstName";
    const USER_LAST_NAME         = "@VarStudentLastName";
    const USER_FULL_NAME         = "@VarStudentFullName";
    const ACTION_LINK            = "@VarActionLink";

    public static function getMockVariables(): array
    {
        $faker = \Faker\Factory::create();
        return [
            self::USER_EMAIL => $faker->email,
            self::USER_FIRST_NAME => $faker->firstName,
            self::USER_LAST_NAME => $faker->lastName,
            self::USER_FULL_NAME => $faker->name,
            self::ACTION_LINK => url('/'),
        ];
    }

    public static function getVariablesFromContent(?User $user = null, ?string $action_link = null): array
    {
        return [
            self::USER_EMAIL => $user->email,
            self::USER_FIRST_NAME => $user->firstName,
            self::USER_LAST_NAME => $user->lastName,
            self::USER_FULL_NAME => $user->name,
            self::ACTION_LINK => $action_link,
        ];
    }

    public static function getType(): string
    {
        return self::TYPE;
    }
}
