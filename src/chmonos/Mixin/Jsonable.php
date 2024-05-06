<?php
namespace ryunosuke\chmonos\Mixin;

use function ryunosuke\chmonos\array_sprintf;

trait Jsonable
{
    public static function encodeJson(mixed $value, int $options = JSON_UNESCAPED_UNICODE): string
    {
        $self = function ($v) use ($options) { return self::encodeJson($v, $options); };

        if (is_iterable($value)) {
            if (is_array($value) && $value === array_values($value)) {
                return '[' . array_sprintf($value, $self, ',') . ']';
            }
            return '{' . array_sprintf($value, function ($v, $k) use ($self) { return $self("$k") . ':' . $self($v); }, ',') . '}';
        }
        if (is_resource($value)) {
            return stream_get_contents($value);
        }
        if (is_float($value) && is_nan($value)) {
            return 'NaN';
        }
        if (is_float($value) && is_infinite($value)) {
            return ($value < 0 ? '-' : '+') . 'Infinity';
        }
        return json_encode($value, $options);
    }

    public static function literalJson(string $value)
    {
        $handle = tmpfile();
        fwrite($handle, $value);
        rewind($handle);
        return $handle;
    }
}
