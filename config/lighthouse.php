<?php declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    */

    'route' => [
        'uri' => '/graphql',
        'name' => 'graphql',
        'middleware' => [
            'web', // Changed from 'api' to support session-based auth
            Nuwave\Lighthouse\Http\Middleware\AcceptJson::class,
            Nuwave\Lighthouse\Http\Middleware\AttemptAuthentication::class,
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => null,

    /*
    |--------------------------------------------------------------------------
    | Schema Path
    |--------------------------------------------------------------------------
    */

    'schema_path' => base_path('graphql/schema.graphql'),

    /*
    |--------------------------------------------------------------------------
    | Schema Cache
    |--------------------------------------------------------------------------
    */

    'schema_cache' => [
        'enable' => env('LIGHTHOUSE_SCHEMA_CACHE_ENABLE', env('APP_ENV') !== 'local'),
        'path' => env(
            'LIGHTHOUSE_SCHEMA_CACHE_PATH',
            base_path('bootstrap/cache/lighthouse-schema.php')
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Directive Tags
    |--------------------------------------------------------------------------
    */

    'cache_directive_tags' => false,

    /*
    |--------------------------------------------------------------------------
    | Query Cache
    |--------------------------------------------------------------------------
    */

    'query_cache' => [
        'enable' => env('LIGHTHOUSE_QUERY_CACHE_ENABLE', true),
        'mode' => env('LIGHTHOUSE_QUERY_CACHE_MODE', 'store'),
        'opcache_path' => env(
            'LIGHTHOUSE_QUERY_CACHE_OPCACHE_PATH',
            base_path('bootstrap/cache')
        ),
        'store' => env('LIGHTHOUSE_QUERY_CACHE_STORE', null),
        'ttl' => env('LIGHTHOUSE_QUERY_CACHE_TTL', 24 * 60 * 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Cache
    |--------------------------------------------------------------------------
    */

    'validation_cache' => [
        'enable' => env('LIGHTHOUSE_VALIDATION_CACHE_ENABLE', false),
        'store' => env('LIGHTHOUSE_VALIDATION_CACHE_STORE', null),
        'ttl' => env('LIGHTHOUSE_VALIDATION_CACHE_TTL', 24 * 60 * 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Parse source location
    |--------------------------------------------------------------------------
    */

    'parse_source_location' => true,

    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    */

    'namespaces' => [
        'models' => ['App', 'App\\Models'],
        'queries' => 'App\\GraphQL\\Queries',
        'mutations' => 'App\\GraphQL\\Mutations',
        'subscriptions' => 'App\\GraphQL\\Subscriptions',
        'types' => 'App\\GraphQL\\Types',
        'interfaces' => 'App\\GraphQL\\Interfaces',
        'unions' => 'App\\GraphQL\\Unions',
        'scalars' => 'App\\GraphQL\\Scalars',
        'directives' => 'App\\GraphQL\\Directives',
        'validators' => 'App\\GraphQL\\Validators',
    ],

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */

    'security' => [
        'max_query_complexity' => GraphQL\Validator\Rules\QueryComplexity::DISABLED,
        'max_query_depth' => GraphQL\Validator\Rules\QueryDepth::DISABLED,
        'disable_introspection' =>
            (bool) env('LIGHTHOUSE_SECURITY_DISABLE_INTROSPECTION', false)
                ? GraphQL\Validator\Rules\DisableIntrospection::ENABLED
                : GraphQL\Validator\Rules\DisableIntrospection::DISABLED,
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    'pagination' => [
        'default_count' => null,
        'max_count' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    */

    'debug' => env(
        'LIGHTHOUSE_DEBUG',
        GraphQL\Error\DebugFlag::INCLUDE_DEBUG_MESSAGE
        | GraphQL\Error\DebugFlag::INCLUDE_TRACE
    ),

    /*
    |--------------------------------------------------------------------------
    | Error Handlers
    |--------------------------------------------------------------------------
    */

    'error_handlers' => [
        Nuwave\Lighthouse\Execution\AuthenticationErrorHandler::class,
        Nuwave\Lighthouse\Execution\AuthorizationErrorHandler::class,
        Nuwave\Lighthouse\Execution\ValidationErrorHandler::class,
        Nuwave\Lighthouse\Execution\ReportingErrorHandler::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Field Middleware
    |--------------------------------------------------------------------------
    */

    'field_middleware' => [
        Nuwave\Lighthouse\Schema\Directives\TrimDirective::class,
        Nuwave\Lighthouse\Schema\Directives\ConvertEmptyStringsToNullDirective::class,
        Nuwave\Lighthouse\Schema\Directives\SanitizeDirective::class,
        Nuwave\Lighthouse\Validation\ValidateDirective::class,
        Nuwave\Lighthouse\Schema\Directives\TransformArgsDirective::class,
        Nuwave\Lighthouse\Schema\Directives\SpreadDirective::class,
        Nuwave\Lighthouse\Schema\Directives\RenameArgsDirective::class,
        Nuwave\Lighthouse\Schema\Directives\DropArgsDirective::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Global ID
    |--------------------------------------------------------------------------
    */

    'global_id_field' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Persisted Queries
    |--------------------------------------------------------------------------
    */

    'persisted_queries' => true,

    /*
    |--------------------------------------------------------------------------
    | Transactional Mutations
    |--------------------------------------------------------------------------
    */

    'transactional_mutations' => true,

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment Protection
    |--------------------------------------------------------------------------
    */

    'force_fill' => true,

    /*
    |--------------------------------------------------------------------------
    | Batchload Relations
    |--------------------------------------------------------------------------
    */

    'batchload_relations' => true,

    /*
    |--------------------------------------------------------------------------
    | Shortcut Foreign Key Selection
    |--------------------------------------------------------------------------
    */

    'shortcut_foreign_key_selection' => false,

];
