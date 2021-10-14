<?php

namespace EscolaLms\TemplatesEmail\Database\Factories;

use EscolaLms\Templates\Database\Factories\TemplateFactory as BaseTemplateFactory;
use EscolaLms\TemplatesEmail\Models\Template;

class TemplateFactory extends BaseTemplateFactory
{

    protected $model = Template::class;

    public function definition()
    {
        return array_merge(parent::definition(), [
            'is_default' => $this->faker->boolean()
        ]);
    }
}
