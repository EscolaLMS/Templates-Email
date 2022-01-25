<?php

return [
    'mjml' => [
        'use_api'          => false,
        //
        'binary_path'      => env('MJML_BINARY_PATH'),
        //
        'api_id'           => env('MJML_API_ID'),
        'api_secret'       => env('MJML_API_SECRET'),
        //
        'default_template' => <<<MJML_TEMPLATE
                                <mjml>
                                <mj-body>
                                    <mj-section background-color="white">
                                        <mj-column>
                                            @VarTemplateContent
                                        </mj-column>
                                    </mj-section>
                                </mj-body>
                                </mjml>
                                MJML_TEMPLATE,

    ]
];
