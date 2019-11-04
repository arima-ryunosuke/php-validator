<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;

/**
 * URI バリデータ
 *
 * - schemes: array
 *   - 許容するスキームを指定
 *   - 例えば ['http', 'https', 'ftp'] とするとこの3スキームしか通らない
 */
class Uri extends AbstractCondition implements Interfaces\ImeMode, Interfaces\InferableType
{
    public const INVALID        = 'UriInvalid';
    public const INVALID_SCHEME = 'UriInvalidScheme';
    public const INVALID_HOST   = 'UriInvalidHost';
    public const INVALID_PORT   = 'UriInvalidPort';

    protected static $messageTemplates = [
        self::INVALID        => 'URLをスキームから正しく入力してください',
        self::INVALID_SCHEME => 'スキームが不正です(%schemes%のみ)',
        self::INVALID_HOST   => 'ホスト名が不正です',
        self::INVALID_PORT   => 'ポート番号が不正です',
    ];

    protected $_schemes;

    public function __construct($schemes = [])
    {
        $this->_schemes = (array) $schemes;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $parsed = parse_url($value);

        if (!$parsed || !isset($parsed['scheme'])) {
            $error($consts['INVALID']);
        }
        else if (count($params['schemes']) && !in_array($parsed['scheme'], $params['schemes'])) {
            $error($consts['INVALID_SCHEME']);
        }
        else if (!isset($parsed['host'])) {
            $error($consts['INVALID_HOST']);
        }
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }

    public function getType()
    {
        return 'url';
    }
}
