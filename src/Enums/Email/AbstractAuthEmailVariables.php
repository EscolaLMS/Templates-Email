<?php

namespace EscolaLms\TemplatesEmail\Enums\Email;

use EscolaLms\Auth\Models\User;
use EscolaLms\Core\Enums\BasicEnum;
use EscolaLms\Templates\Enum\Contracts\TemplateVariableContract;
use Illuminate\Support\Str;

abstract class AbstractAuthEmailVariables extends BasicEnum implements TemplateVariableContract
{
    //
    const TYPE = 'email';

    //
    const VAR_USER_EMAIL      = "@VarUserEmail";
    const VAR_USER_FIRST_NAME = "@VarStudentFirstName";
    const VAR_USER_LAST_NAME  = "@VarStudentLastName";
    const VAR_USER_FULL_NAME  = "@VarStudentFullName";
    const VAR_ACTION_LINK     = "@VarActionLink";

    public static function getMockVariables(): array
    {
        $faker = \Faker\Factory::create();
        return [
            self::VAR_USER_EMAIL => $faker->email,
            self::VAR_USER_FIRST_NAME => $faker->firstName,
            self::VAR_USER_LAST_NAME => $faker->lastName,
            self::VAR_USER_FULL_NAME => $faker->name,
            self::VAR_ACTION_LINK => url('/'),
        ];
    }

    public static function getVariablesFromContent(?User $user = null, ?string $action_link = null): array
    {
        return [
            self::VAR_USER_EMAIL => $user->email,
            self::VAR_USER_FIRST_NAME => $user->firstName,
            self::VAR_USER_LAST_NAME => $user->lastName,
            self::VAR_USER_FULL_NAME => $user->name,
            self::VAR_ACTION_LINK => $action_link,
        ];
    }

    public static function getRequiredVariables(): array
    {
        return [
            self::VAR_ACTION_LINK,
        ];
    }

    public static function isValid(string $content): bool
    {
        return Str::containsAll($content, self::getRequiredVariables());
    }

    public static function getType(): string
    {
        return self::TYPE;
    }

    abstract public static function getVarSet(): string;
}
