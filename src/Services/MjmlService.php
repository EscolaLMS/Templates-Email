<?php

namespace EscolaLms\TemplatesEmail\Services;

use EscolaLms\TemplatesEmail\EscolaLmsTemplatesEmailServiceProvider;
use EscolaLms\TemplatesEmail\Mjml\BinaryRenderer;
use Exception;
use Qferrer\Mjml\Renderer\ApiRenderer;

class MjmlService
{
    public function render(string $mjml): string
    {
        if (config(EscolaLmsTemplatesEmailServiceProvider::CONFIG_KEY . '.mjml.use_api')) {
            $apiId = config(EscolaLmsTemplatesEmailServiceProvider::CONFIG_KEY . '.mjml.api_id');
            $apiSecret = config(EscolaLmsTemplatesEmailServiceProvider::CONFIG_KEY . '.mjml.api_secret');

            if (empty($apiId) || empty($apiSecret)) {
                throw new Exception('Missing MJML API id and/or secret');
            }

            $renderer = new ApiRenderer($apiId, $apiSecret);
        } else {
            $renderer = new BinaryRenderer();
        }
        return $renderer->render($mjml);
    }
}
