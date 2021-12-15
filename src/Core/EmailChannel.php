<?php

namespace EscolaLms\TemplatesEmail\Core;

use EscolaLms\Templates\Contracts\TemplateChannelContract;
use EscolaLms\Templates\Core\AbstractTemplateChannelClass;
use EscolaLms\Templates\Enums\TemplateSectionTypeEnum;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\TemplatesEmail\Services\Contracts\MjmlServiceContract;
use Exception;
use HTMLPurifier_Config;
use HTMLPurifier;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailChannel extends AbstractTemplateChannelClass implements TemplateChannelContract
{
    public static function send(EventWrapper $event, array $sections): bool
    {
        if (!Arr::has($sections, self::sectionsRequired())) {
            return false;
        }

        $data = self::preview($event, $sections);

        $mailable = new EmailMailable();
        $mailable->to($data['to']);
        $mailable->subject($data['subject']);
        $mailable->html($data['html']);

        Mail::send($mailable);

        return true;
    }

    public static function preview(EventWrapper $event, array $sections): array
    {
        if (!Arr::has($sections, self::sectionsRequired())) {
            throw new Exception('Missing sections');
        }

        if (Str::contains($sections['content'], '<mjml>')) {
            $html = self::renderMjml($sections['content']);
        } else {
            $html = self::fixHtml($sections['content']);
        }

        return [
            'to' => $event->getUser()->email,
            'subject' => trim($sections['title']),
            'html' => $html,
        ];
    }

    private static function renderMjml(string $mjml): string
    {
        return app(MjmlServiceContract::class)->render($mjml);
    }

    private static function fixHtml(string $html): string
    {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($html);
    }

    public static function sections(): array
    {
        return [
            'title'   => TemplateSectionTypeEnum::SECTION_TEXT,
            'content' => TemplateSectionTypeEnum::SECTION_HTML,
        ];
    }

    public static function sectionsRequired(): array
    {
        return ['title', 'content'];
    }
}
