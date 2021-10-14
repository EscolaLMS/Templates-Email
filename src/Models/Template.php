<?php

namespace EscolaLms\TemplatesEmail\Models;

use EscolaLms\Templates\Models\Template as BaseTemplate;

class Template extends BaseTemplate
{
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'type' => 'string',
        'vars_set' => 'string',
        'content' => 'string',
        'is_default' => 'boolean',
    ];

    public $fillable = [
        'name',
        'type',
        'vars_set',
        'content',
        'is_default',
    ];
}
