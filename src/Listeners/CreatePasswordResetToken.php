<?php

namespace EscolaLms\TemplatesEmail\Listeners;

use EscolaLms\Auth\Events\PasswordForgotten;
use EscolaLms\Auth\Listeners\CreatePasswordResetToken as AuthCreatePasswordResetToken;
use EscolaLms\Auth\Repositories\Contracts\UserRepositoryContract;
use EscolaLms\TemplatesEmail\Notifications\ResetPassword;
use Illuminate\Support\Str;

class CreatePasswordResetToken extends AuthCreatePasswordResetToken
{
    private UserRepositoryContract $userRepository;

    public function __construct(UserRepositoryContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(PasswordForgotten $event): void
    {
        /** @var \EscolaLms\Auth\Models\User $user */
        $user = $event->getUser();

        $this->userRepository->update([
            'password_reset_token' => Str::random(32),
        ], $user->getKey());

        $user->refresh();

        $user->notify(new ResetPassword($user->password_reset_token, $event->getReturnUrl()));
    }
}
