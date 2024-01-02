<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 文字数バリデータ
 *
 * - min: int|null
 *   - 文字数の最小数
 * - max: int|null
 *   - 文字数の最大数
 * - grapheme: bool
 *   - 書記素単位か？
 */
class StringLength extends AbstractCondition implements Interfaces\MaxLength
{
    public const INVALID   = 'StringLengthInvalidLength';
    public const TOO_SHORT = 'StringLengthInvalidMin';
    public const TOO_LONG  = 'StringLengthInvalidMax';
    public const SHORTLONG = 'StringLengthInvalidMinMax';
    public const DIFFERENT = 'StringLengthInvalidDifferenr';

    protected static $messageTemplates = [
        self::INVALID   => 'Invalid value given',
        self::TOO_SHORT => '%min%文字以上で入力して下さい',
        self::TOO_LONG  => '%max%文字以下で入力して下さい',
        self::SHORTLONG => '%min%文字～%max%文字で入力して下さい',
        self::DIFFERENT => '%min%文字で入力して下さい',
    ];

    protected $_min;
    protected $_max;
    protected $_grapheme;

    public function __construct($min = null, $max = null, /*for compatible default true in future scope*/ $grapheme = false)
    {
        $this->_min = $min;
        $this->_max = $max;
        $this->_grapheme = $grapheme;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $length = $params['grapheme'] ? grapheme_strlen($value) : mb_strlen($value);

        if (!is_null($params['max']) && !is_null($params['min']) && ($length > $params['max'] || $length < $params['min'])) {
            if ($params['min'] === $params['max']) {
                $error($consts['DIFFERENT']);
            }
            else {
                $error($consts['SHORTLONG']);
            }
        }
        else if (is_null($params['max']) && !is_null($params['min']) && $length < $params['min']) {
            $error($consts['TOO_SHORT']);
        }
        else if (is_null($params['min']) && !is_null($params['max']) && $length > $params['max']) {
            $error($consts['TOO_LONG']);
        }
    }

    public function getMaxLength()
    {
        // maxlength が対応していないので設定のしようがない
        if ($this->_grapheme) {
            return null;
        }
        return $this->_max;
    }

    public function getFixture($value, $fields)
    {
        $value = mb_substr($value, 0, $this->_max ?? PHP_INT_MAX);
        $value = str_pad($value, $this->_min ?? 0, 'X'); // mb_str_pad in future scope
        return $value;
    }
}
