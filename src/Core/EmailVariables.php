<?php

namespace EscolaLms\TemplatesEmail\Core;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Contracts\TemplateVariableContract;
use EscolaLms\Templates\Core\AbstractTemplateVariableClass;
use EscolaLms\Templates\Core\SettingsVariables;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\TemplatesEmail\EscolaLmsTemplatesEmailServiceProvider;
use Illuminate\Support\Str;

abstract class EmailVariables extends AbstractTemplateVariableClass implements TemplateVariableContract
{
    public static function mockedVariables(?User $user = null): array
    {
        return SettingsVariables::getSettingsValues();
    }

    public static function variablesFromEvent(EventWrapper $event): array
    {
        return SettingsVariables::getSettingsValues();
    }

    public static function requiredSections(): array
    {
        return [];
    }

    public static function wrapWithMjml(string $content = ''): string
    {
        if (!Str::contains($content, ['<mj-text'])) {
            $content = '<mj-text>' . $content . '</mj-text>';
        }

        $template = config(
            EscolaLmsTemplatesEmailServiceProvider::CONFIG_KEY . '.mjml.default_template',
            <<<MJML_TEMPLATE
            <mjml>
            <mj-body>
                <mj-section background-color="white">
                    <mj-column>
                        @VarTemplateContent
                    </mj-column>
                </mj-section>
            </mj-body>
            </mjml>
            MJML_TEMPLATE
        );

        return str_replace('@VarTemplateContent', $content, $template);
    }
}
