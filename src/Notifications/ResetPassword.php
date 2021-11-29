<?php

namespace EscolaLms\TemplatesEmail\Notifications;

use EscolaLms\Auth\Notifications\ResetPassword as AuthResetPassword;
use EscolaLms\Notifications\Core\NotificationContract;
use EscolaLms\Notifications\Core\Traits\NotificationDefaultImplementation;
use EscolaLms\Notifications\Facades\EscolaLmsNotifications;
use EscolaLms\TemplatesEmail\Enums\Email\ResetPasswordVariables;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends AuthResetPassword implements NotificationContract
{
    use NotificationDefaultImplementation {
        toMail as toMailTrait;
    }

    public function toMail($notifiable): MailMessage
    {
        $template = EscolaLmsNotifications::findTemplateForNotification($this, 'mail');

        if ($template && $template->is_valid) {
            return $this->toMailTrait($notifiable);
        }

        return parent::toMail($notifiable);
    }

    public function additionalDataForVariables($notifiable): array
    {
        return [
            $this->resetUrl($notifiable),
        ];
    }

    public static function defaultTitleTemplate(): string
    {
        return Lang::get('Reset Password Notification');
    }

    public static function defaultContentTemplate(): string
    {
        return '';
    }

    public static function templateVariablesClass(): string
    {
        return ResetPasswordVariables::class;
    }
}
