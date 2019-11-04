<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 正規表現バリデータ
 *
 * - pattern: string
 *   - 正規表現文字列
 * - negation: bool
 *   - 否定フラグ。 true にすると「マッチする場合 invalid」とみなす
 */
class Regex extends AbstractCondition
{
    public const INVALID   = 'regexInvalid';
    public const ERROROUS  = 'regexErrorous';
    public const NOT_MATCH = 'regexNotMatch';
    public const NEGATION  = 'regexNegation';

    protected static $messageTemplates = [
        self::INVALID   => 'Invalid value given',
        self::ERROROUS  => 'There was%pattern%\'',
        self::NOT_MATCH => 'パターンに一致しません',
        self::NEGATION  => '使用できない文字が含まれています',
    ];

    protected $_pattern;
    protected $_negation;

    public function __construct($pattern, $negation = false)
    {
        $this->_pattern = $pattern;
        $this->_negation = $negation;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            return $error($consts['INVALID']);
        }

        $status = preg_match($params['pattern'], $value);
        if (false === $status) {
            $error($consts['ERROROUS']);
        }
        else if (!$params['negation'] && !$status) {
            $error($consts['NOT_MATCH']);
        }
        else if ($params['negation'] && $status) {
            $error($consts['NEGATION']);
        }
    }
}
