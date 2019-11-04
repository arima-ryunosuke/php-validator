<?php
namespace ryunosuke\chmonos\Mixin;

use function ryunosuke\chmonos\array_sprintf;

trait Htmlable
{
    public static function createHtmlAttr($attrs, $arg = null)
    {
        $attrs = array_filter($attrs, function ($v) { return $v !== false; });

        return array_sprintf($attrs, function ($v, $k) use ($arg) {
            if ($v === true) {
                return self::escapeHtml($k);
            }
            if ($k === 'style' && is_array($v)) {
                $v = self::createStyleAttr($v);
            }
            if ($arg !== null && strtolower($k) !== 'value') {
                $v = sprintf($v, $arg);
            }
            return self::escapeHtml($k) . '="' . self::escapeHtml($v) . '"';
        }, ' ');
    }

    public static function createStyleAttr($styles)
    {
        return array_sprintf((array) $styles, function ($style, $key) {
            return is_int($key) ? $style : "$key:$style";
        }, ';');
    }

    public static function escapeHtml($value, $glue = ' ')
    {
        if (is_array($value)) {
            $value = array_map(function ($v) use ($glue) { return self::escapeHtml($v, $glue); }, $value);
            if ($glue === null) {
                return $value;
            }
            return implode($glue, $value);
        }
        return htmlspecialchars($value, ENT_QUOTES);
    }
}
