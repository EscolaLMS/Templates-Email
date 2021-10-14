<?php

namespace EscolaLms\TemplatesEmail\Notifications;

use EscolaLms\Auth\Notifications\ResetPassword as AuthResetPassword;
use EscolaLms\TemplatesEmail\Enums\Email\ResetPasswordVariables;
use EscolaLms\TemplatesEmail\Repositories\Contracts\EmailTemplateRepositoryContract;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;

class ResetPassword extends AuthResetPassword
{
    private EmailTemplateRepositoryContract $templateRepository;

    public function __construct(string $token, ?string $url)
    {
        parent::__construct($token, $url);
        $this->templateRepository = app(EmailTemplateRepositoryContract::class);
    }

    public function toMail($notifiable)
    {
        $template = $this->templateRepository->findDefaultForTypeAndSubtype(ResetPasswordVariables::getType(), ResetPasswordVariables::getSubtype());
        if ($template) {
            $vars = ResetPasswordVariables::getVariablesFromContent($notifiable, $this->resetUrl($notifiable));
            $body = strtr($template->content, $vars);
            return (new MailMessage)
                ->subject(Lang::get('Reset Password Notification'))
                ->line(new HtmlString($body));
        }

        return parent::toMail($notifiable);
    }
}
