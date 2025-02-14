<?php

namespace EscolaLms\TemplatesEmail\Services;

use Qferrer\Mjml\ApiInterface;
use Qferrer\Mjml\RendererInterface;

/**
 * Class ApiRenderer
 */
class CurlRenderer implements RendererInterface
{
    /**
     * The HTTP client.
     *
     * @var ApiInterface
     */
    private $client;

    /**
     * ApiRenderer constructor.
     *
     * @param ApiInterface $httpClient
     */
    public function __construct(ApiInterface $httpClient)
    {
        $this->client = $httpClient;
    }

    /**
     * @inheritDoc
     */
    public function render(string $content): string
    {
        return $this->client->getHtml($content);
    }
}
