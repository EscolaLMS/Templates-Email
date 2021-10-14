<?php

namespace EscolaLms\TemplatesEmail\Repositories\Contracts;

use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract;
use EscolaLms\TemplatesEmail\Models\Template;

interface EmailTemplateRepositoryContract extends TemplateRepositoryContract
{
    public function findDefaultForTypeAndSubtype(string $type, string $subtype): ?Template;
}
