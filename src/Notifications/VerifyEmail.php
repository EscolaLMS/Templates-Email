<?php

namespace EscolaLms\TemplatesEmail\Notifications;

use EscolaLms\TemplatesEmail\Enums\Email\VerifyEmailVariables;
use EscolaLms\TemplatesEmail\Repositories\Contracts\EmailTemplateRepositoryContract;
use Illuminate\Auth\Notifications\VerifyEmail as LaravelVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;

class VerifyEmail extends LaravelVerifyEmail
{
    private EmailTemplateRepositoryContract $templateRepository;

    public function __construct()
    {
        $this->templateRepository = app(EmailTemplateRepositoryContract::class);
    }

    public function toMail($notifiable)
    {
        $template = $this->templateRepository->findDefaultForTypeAndSubtype(VerifyEmailVariables::getType(), VerifyEmailVariables::getSubtype());
        if ($template) {
            $vars = VerifyEmailVariables::getVariablesFromContent($notifiable, $this->verificationUrl($notifiable));
            $body = strtr($template->content, $vars);
            return (new MailMessage)
                ->subject(Lang::get('Verify Email Address'))
                ->line(new HtmlString($body));
        }

        return parent::toMail($notifiable);
    }
}
