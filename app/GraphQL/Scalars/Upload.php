<?php

declare(strict_types=1);

namespace App\GraphQL\Scalars;

use GraphQL\Type\Definition\ScalarType;
use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use Illuminate\Http\UploadedFile;

/**
 * Upload scalar for file uploads via GraphQL
 * Works with lighthouse's multipart form data handling
 */
class Upload extends ScalarType
{
    public string $name = 'Upload';
    public ?string $description = 'A file upload scalar, used for GraphQL multipart form requests.';

    /**
     * Serializes an internal value to include in a response.
     * This scalar is input-only, so serialization is not applicable.
     */
    public function serialize(mixed $value): never
    {
        throw new Error('Upload scalar cannot be serialized.');
    }

    /**
     * Parses an externally provided value (query variable) to use as an input.
     * The file is already parsed by the lighthouse multipart middleware.
     */
    public function parseValue(mixed $value): UploadedFile
    {
        if (!$value instanceof UploadedFile) {
            throw new Error('Could not process upload. Expected UploadedFile instance.');
        }

        return $value;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     * Files cannot be provided as literal values in queries.
     */
    public function parseLiteral(Node $valueNode, ?array $variables = null): never
    {
        throw new Error('Upload literals are not supported. Use variable instead.');
    }
}
