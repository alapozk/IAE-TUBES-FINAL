<?php

declare(strict_types=1);

namespace App\GraphQL\Scalars;

use GraphQL\Type\Definition\ScalarType;
use GraphQL\Language\AST\StringValueNode;
use Carbon\Carbon;

class DateTime extends ScalarType
{
    public string $name = 'DateTime';
    public ?string $description = 'A datetime string with format Y-m-d H:i:s, e.g. "2021-01-31 12:00:00".';

    /**
     * Serializes an internal value to include in a response.
     */
    public function serialize(mixed $value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }
        
        if (is_string($value)) {
            return $value;
        }
        
        return (string) $value;
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     */
    public function parseValue(mixed $value): ?Carbon
    {
        if ($value === null) {
            return null;
        }
        
        return Carbon::parse($value);
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     */
    public function parseLiteral(mixed $valueNode, ?array $variables = null): ?string
    {
        if ($valueNode instanceof StringValueNode) {
            return $valueNode->value;
        }
        
        return null;
    }
}
