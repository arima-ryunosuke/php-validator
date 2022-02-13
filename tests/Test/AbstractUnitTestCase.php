<?php
namespace ryunosuke\Test;

abstract class AbstractUnitTestCase extends \PHPUnit\Framework\TestCase
{
    public static function assertException($e, $callback)
    {
        if (is_string($e)) {
            if (class_exists($e)) {
                $ref = new \ReflectionClass($e);
                $e = $ref->newInstanceWithoutConstructor();
            }
            else {
                $e = new \Exception($e);
            }
        }

        $args = array_slice(func_get_args(), 2);
        $message = json_encode($args, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        try {
            $callback(...$args);
        }
        catch (\Throwable $ex) {
            // 型は常に判定
            self::assertInstanceOf(get_class($e), $ex, $message);
            // コードは指定されていたときのみ
            if ($e->getCode() > 0) {
                self::assertEquals($e->getCode(), $ex->getCode(), $message);
            }
            // メッセージも指定されていたときのみ
            if (strlen($e->getMessage()) > 0) {
                self::assertStringContainsString($e->getMessage(), $ex->getMessage(), $message);
            }
            return;
        }
        self::fail(get_class($e) . ' is not thrown.' . $message);
    }

    public static function assertAttribute($expected, $actual)
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($actual);
        libxml_clear_errors();
        $attributes = [];
        foreach ($dom->getElementsByTagName('*') as $tag) {
            if (isset($expected[$tag->tagName])) {
                $attrs = [];
                foreach ($tag->attributes as $attr) {
                    $attrs[$attr->name] = $attr->value;
                }
                $attributes[$tag->tagName][] = $attrs;
            }
        }
        foreach ($expected as $tagname => $attrs) {
            self::assertArrayHasKey($tagname, $attributes);
            foreach ($attrs as $n => $attr) {
                self::assertEquals($attr, $attributes[$tagname][$n], "actual:$tagname/$n " . var_export($attributes[$tagname][$n], true));
            }
        }
    }

    public static function publishField($class, $field, $value = null)
    {
        $ref = new \ReflectionProperty($class, $field);
        $ref->setAccessible(true);
        if (func_num_args() === 2) {
            if ($ref->isStatic()) {
                return $ref->getValue();
            }
            else {
                return $ref->getValue($class);
            }
        }
        else {
            if ($ref->isStatic()) {
                return $ref->setValue($value);
            }
            else {
                return $ref->setValue($class, $value);
            }
        }
    }

    public static function publishMethod($class, $method)
    {
        $ref = new \ReflectionMethod($class, $method);
        $ref->setAccessible(true);
        if ($ref->isStatic()) {
            return $ref->getClosure();
        }
        else {
            return $ref->getClosure($class);
        }
    }
}
