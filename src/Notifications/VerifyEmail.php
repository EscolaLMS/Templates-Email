<?php

namespace EscolaLms\TemplatesEmail\Notifications;

use EscolaLms\Notifications\Core\NotificationContract;
use EscolaLms\Notifications\Core\Traits\NotificationDefaultImplementation;
use EscolaLms\Notifications\Facades\EscolaLmsNotifications;
use EscolaLms\TemplatesEmail\Enums\Email\VerifyEmailVariables;
use Illuminate\Auth\Notifications\VerifyEmail as LaravelVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class VerifyEmail extends LaravelVerifyEmail implements NotificationContract
{
    use NotificationDefaultImplementation {
        toMail as toMailTrait;
    }

    public function toMail($notifiable): MailMessage
    {
        $template = EscolaLmsNotifications::findTemplateForNotification($this, 'mail');

        if ($template && $template->is_valid && $template->title_is_valid) {
            return $this->toMailTrait($notifiable);
        }

        return parent::toMail($notifiable);
    }

    public function additionalDataForVariables($notifiable): array
    {
        return [
            $this->verificationUrl($notifiable),
        ];
    }

    public static function defaultTitleTemplate(): string
    {
        return Lang::get('Verify Email Address');
    }

    public static function defaultContentTemplate(): string
    {
        return Lang::get('Please click the button below to verify your email address.')
            . PHP_EOL
            . VerifyEmailVariables::VAR_ACTION_LINK
            . PHP_EOL
            . Lang::get('If you did not create an account, no further action is required.');
    }

    public static function templateVariablesClass(): string
    {
        return VerifyEmailVariables::class;
    }
}
