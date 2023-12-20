<?php
namespace ryunosuke\chmonos\Condition;

/**
 * URI バリデータ
 *
 * - schemes: array
 *   - 許容するスキームを指定
 *   - 例えば ['http', 'https', 'ftp'] とするとこの3スキームしか通らない
 * - delimiter: ?string
 *   - 非 null を渡すと複数値が許容され、指定文字がデリミタ（正規表現）として使用される
 *   - どのような文字を渡しても空白文字は取り除かれる（"," と ", " は実質同じ意味になる）
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
    protected $_delimiter;

    public function __construct($schemes = [], $delimiter = null)
    {
        $this->_schemes = (array) $schemes;
        $this->_delimiter = $delimiter;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if ($params['delimiter'] === null) {
            $value = $context['cast']('array', $value);
        }
        else {
            $value = array_filter(array_map(fn($v) => trim($v), preg_split($params['delimiter'], $value)), fn($v) => strlen($v));
        }

        $context['foreach']($value, function ($key, $value, $params, $error, $consts) {
            $parsed = parse_url($value);

            if (!$parsed || !isset($parsed['scheme'])) {
                $error($consts['INVALID']);
                return false;
            }
            else if (count($params['schemes']) && !in_array($parsed['scheme'], $params['schemes'])) {
                $error($consts['INVALID_SCHEME']);
                return false;
            }
            else if (!isset($parsed['host'])) {
                $error($consts['INVALID_HOST']);
                return false;
            }
        }, $params, $error, $consts);
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
