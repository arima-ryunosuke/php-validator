<?php
namespace ryunosuke\chmonos\Mixin;

trait Fixturable
{
    public static function fixtureBool(): bool
    {
        return self::__next(__FUNCTION__);
    }

    public static function fixtureInt(int $min, int $max): int
    {
        assert($min <= $max);
        if ($max - $min > 65535) {
            return rand($min, $max);
        }
        return self::__next(__FUNCTION__, $min, $max);
    }

    public static function fixtureFloat(float $min, float $max): float
    {
        $minint = (int) $min;
        $maxint = (int) $max;
        $mindec = $min - $minint;
        $maxdec = $max - $maxint;
        $int = self::fixtureInt($minint, $maxint);
        $dec = $mindec + (rand() / getrandmax()) * ($maxdec - $mindec);
        return $int + $dec;
    }

    public static function fixtureDecimal(int $int, int $dec): string
    {
        $digit = (10 ** $int) - (10 ** -$dec);
        return sprintf("%.{$dec}F", self::fixtureFloat(-$digit, $digit));
    }

    public static function fixtureString(int $length, string $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"): string
    {
        assert(strlen($chars) < 1024 * 1024 * 10);
        $result = '';
        while (strlen($result) < $length) {
            $result .= self::__next(__FUNCTION__, $chars);
        }
        return $result;
    }

    /**
     * @template T
     * @param T[] $array
     * @return T|T[]
     */
    public static function fixtureArray(array $array, ?int $count = null)
    {
        if ($count === null) {
            return self::__next(__FUNCTION__, $array);
        }

        $result = [];
        while (count($result) < $count) {
            $result[] = self::__next(__FUNCTION__, $array);
        }
        return $result;
    }

    private static function __next(string $type, ...$sources)
    {
        $storer = new class() {
            public static $buckets = [];
        };
        $buckets = &$storer::$buckets;

        $generateInfiniteShuffleIterator = static function (array $array) {
            while (shuffle($array)) {
                yield from $array;
            }
        };

        $key = serialize([$type => $sources]);
        assert(strlen($key) < 1024 * 1024 * 10);
        $iterator = $buckets[$key] ??= (function ($type, $sources) use ($generateInfiniteShuffleIterator) {
            switch ($type) {
                case 'fixtureBool':
                    return $generateInfiniteShuffleIterator([false, true]);
                case 'fixtureInt':
                    return $generateInfiniteShuffleIterator(range($sources[0], $sources[1]));
                case 'fixtureString':
                    return $generateInfiniteShuffleIterator(str_split($sources[0]));
                case 'fixtureArray':
                    return $generateInfiniteShuffleIterator($sources[0]);
            }
        })($type, $sources);

        $current = $iterator->current();
        $iterator->next();
        return $current;
    }
}
