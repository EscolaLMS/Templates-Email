<?php

namespace EscolaLms\TemplatesEmail\Core;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Contracts\TemplateVariableContract;
use EscolaLms\Templates\Core\AbstractTemplateVariableClass;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\TemplatesEmail\EscolaLmsTemplatesEmailServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

abstract class EmailVariables extends AbstractTemplateVariableClass implements TemplateVariableContract
{
    const VAR_APP_NAME  = '@VarAppName';
    const VAR_LOGO      = '@VarLogo';

    public static function mockedVariables(?User $user = null): array
    {
        return [
            self::VAR_APP_NAME => config('app.name'),
            self::VAR_LOGO => config('global.logo'),
        ];
    }

    public static function variablesFromEvent(EventWrapper $event): array
    {
        $logo = config('global.logo');
        if (Storage::exists($logo)) {
            $logo = Storage::url($logo);
        }
        else if (File::exists(public_path($logo))) {
            $logo = URL::asset($logo);
        }

        return [
            self::VAR_APP_NAME => config('app.name'),
            self::VAR_LOGO => $logo
        ];
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
                <mj-section background-color="#f0f0f0">
                    <mj-column>
                        <mj-text font-size="20px" color="#626262">
                            @VarAppName
                        </mj-text>
                    </mj-column>
                </mj-section>
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
