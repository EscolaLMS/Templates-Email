<?php

namespace EscolaLms\TemplatesEmail\Repositories;

use EscolaLms\Templates\Repository\TemplateRepository as TemplatesTemplateRepository;
use EscolaLms\TemplatesEmail\Models\Template;
use EscolaLms\TemplatesEmail\Repositories\Contracts\EmailTemplateRepositoryContract;

class EmailTemplateRepository extends TemplatesTemplateRepository implements EmailTemplateRepositoryContract
{
    public function model()
    {
        return Template::class;
    }

    public function findDefaultForTypeAndSubtype(string $type, string $subtype): ?Template
    {
        return $this->allQuery()
            ->where('type', $type)
            ->where('vars_set', $subtype)
            ->where('is_default', true)
            ->first();
    }
}
