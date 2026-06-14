<?php

return [
    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Absensi Guru API',
            ],

            'routes' => [
                /*
                 * Route for accessing api documentation interface
                 */
                'api' => 'api/documentation',

                /*
                 * Route for accessing parsed api-docs.json document
                 */
                'docs' => 'api/docs',

                /*
                 * Route for Oauth2 authentication callback
                 */
                'oauth2_callback' => 'api/oauth2-callback',

                /*
                 * Middleware allows to prevent unexpected access to API documentation
                 */
                'middleware' => [
                    'api' => [],
                    'asset' => [],
                    'docs' => [],
                    'oauth2_callback' => [],
                ],

                'group_options' => [],
            ],

            'paths' => [
                /*
                 * Edit to include full path of the directory where you exported the swagger spec
                 */
                'docs' => storage_path('api-docs'),

                /*
                 * File name of the generated json documentation file
                 */
                'docs_json' => 'api-docs.json',

                /*
                 * File name of the generated YAML documentation file
                 */
                'docs_yaml' => 'api-docs.yaml',

                /*
                 * Set this to `json` or `yaml` to determine which documentation file to use in UI
                 */
                'format_to_use_for_ui' => env('L5_FORMAT_TO_USE_FOR_UI', 'json'),

                /*
                 * Absolute paths to directory containing the swagger annotations are stored.
                 */
                'annotations' => [
                    base_path('app'),
                ],

                /*
                 * Absolute paths to files containing the swagger annotations are stored.
                 */
                'base' => null,

                /*
                 * Absolute path to directory where to export views
                 */
                'views' => base_path('resources/views/vendor/l5-swagger'),

                /*
                 * Edit to set the api's base path
                 */
                'base_path' => env('L5_SWAGGER_BASE_PATH', null),

                /*
                 * Absolute path to directories that you would like to exclude from swagger generation
                 */
                'excludes' => [],
            ],

            'scanOptions' => [
                /**
                 * For supported processors @see https://zircote.github.io/swagger-php/reference/processors.html
                 */
                'processors' => [],

                /*
                 * PHP version to use for scanning files
                 */
                'version' => \OpenApi\Generator::UNDEFINED,

                /*
                 * You can set the Open API version to be used in the generated docs here.
                 * @see https://swagger.io/specification/
                 */
                'open_api_spec_version' => env('L5_SWAGGER_OPEN_API_SPEC_VERSION', \L5Swagger\Generator::OPEN_API_DEFAULT_SPEC_VERSION),

                /*
                 * Analysis result
                 */
                'analyser' => null,

                /*
                 * Analysis result analysis
                 */
                'analysis' => null,

                /*
                 * Alter how the spec is generated
                 */
                'alternatives' => [],

                /*
                 * This option is useful if you want to distinguish between
                 * development and production environments.
                 */
                'exclude' => [],

                /*
                 * Include @OA\PathItem even if no route for it could be found
                 */
                'pattern' => null,

                'logger' => null,
            ],

            /*
             * API security definitions. Will be generated into documentation file.
             * For valid structure @see https://swagger.io/docs/specification/authentication
             */
            'securityDefinitions' => [
                'securitySchemes' => [
                    'sanctum' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'description' => 'Laravel Sanctum - masukkan token: Bearer {token}',
                    ],
                ],

                'security' => [
                    [
                        'sanctum' => [],
                    ],
                ],
            ],

            /*
             * Turn this off to prevent the swagger-ui from being created.
             */
            'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),

            /*
             * Turn this on to generate a copy of documentation always when application is in debug mode.
             * Is useful in development environment. For production make sure to turn it off.
             */
            'generate_always_paths' => [],

            /*
             * Set this to `true` in development mode so that docs would be regenerated on each request
             * Set this to `false` to disable OpenApi generation on production
             */
            'proxy' => false,

            /*
             * Configs plugin allows to fetch external configs instead of passing them to SwaggerUIBundle.
             * See more at: https://github.com/swagger-api/swagger-ui#configs-plugin
             */
            'additional_config_url' => null,

            /*
             * Apply a sort to the operation list of each API. It can be 'alpha' (sort by paths alphanumerically),
             * 'method' (sort by HTTP method).
             * Default is the order returned by the server unchanged.
             */
            'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),

            /*
             * Pass the validatorUrl parameter to SwaggerUi init on the JS side.
             * A null value here disables validation.
             */
            'validator_url' => null,

            /*
             * Float number between 0 and 1, with 1 meaning "zoom in as much as possible".
             */
            'ui' => [
                'display' => [
                    /*
                     * Controls the default expansion setting for the operations and tags.
                     * It can be 'list' (expands only the tags),
                     * 'full' (expands the tags and operations) or 'none' (expands nothing).
                     */
                    'doc_expansion' => env('L5_SWAGGER_UI_DOC_EXPANSION', 'none'),

                    /**
                     * If set, enables filtering. The top bar will show an edit box that
                     * you can use to filter the tagged operations that are shown. Can be
                     * Boolean to enable or disable, or a string, in which case filtering
                     * will be enabled using that string as the filter expression. Filtering is
                     * case-sensitive matching the filter expression anywhere inside the tag.
                     */
                    'filter' => env('L5_SWAGGER_UI_FILTERS', true),
                ],

                'authorization' => [
                    /*
                     * If set to true, it persists authorization data, and it would not be lost on browser close/refresh
                     */
                    'persist_authorization' => env('L5_SWAGGER_UI_PERSIST_AUTHORIZATION', false),

                    'oauth2' => [
                        /*
                         * If set to true, adds PKCE with S256 challenge method to OAuth flows.
                         */
                        'use_pkce_with_authorization_code_grant' => false,
                    ],
                ],
            ],

            /*
             * Constants which can be used in annotations
             */
            'constants' => [
                'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://localhost:8000/api/v1'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Swagger UI Assets Path
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'routes' => [
            'docs' => 'docs.json',
            'oauth2_callback' => 'api/oauth2-callback',

            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],

            'group_options' => [],
        ],

        'paths' => [
            'docs' => storage_path('api-docs'),
            'views' => base_path('resources/views/vendor/l5-swagger'),
            'base' => null,
            'excludes' => [],
        ],

        'scanOptions' => [
            'analyser' => null,
            'analysis' => null,
            'alternatives' => [],
            'exclude' => [],
            'pattern' => null,
            'logger' => null,
            'processors' => [],
            'open_api_spec_version' => \L5Swagger\Generator::OPEN_API_DEFAULT_SPEC_VERSION,
        ],

        'securityDefinitions' => [
            'securitySchemes' => [],
            'security' => [],
        ],

        'generate_always' => false,
        'proxy' => false,
        'additional_config_url' => null,
        'operations_sort' => null,
        'validator_url' => null,

        'ui' => [
            'display' => [
                'doc_expansion' => 'none',
                'filter' => true,
            ],
            'authorization' => [
                'persist_authorization' => false,
                'oauth2' => [
                    'use_pkce_with_authorization_code_grant' => false,
                ],
            ],
        ],

        'constants' => [],
    ],
];
