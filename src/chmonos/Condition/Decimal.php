<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 小数バリデータ
 *
 * 整数部と小数部の桁数を指定して検証する。
 *
 * - int: int
 *   - 整数部の桁数
 * - dec: int
 *   - 小数部の桁数
 */
class Decimal extends AbstractCondition implements Interfaces\MaxLength, Interfaces\InferableType, Interfaces\Range
{
    public const INVALID        = 'DecimalInvalid';
    public const INVALID_INT    = 'DecimalInvalidInt';
    public const INVALID_DEC    = 'DecimalInvalidDec';
    public const INVALID_INTDEC = 'DecimalInvalidIntDec';

    protected static $messageTemplates = [
        self::INVALID        => '小数値を入力してください',
        self::INVALID_INT    => '整数部分を${_int}桁以下で入力してください',
        self::INVALID_DEC    => '小数部分を${_dec}桁以下で入力してください',
        self::INVALID_INTDEC => '整数部分を${_int}桁、小数部分を${_dec}桁以下で入力してください',
    ];

    protected $_int;
    protected $_dec;

    public function __construct($int, $dec)
    {
        $this->_int = $int;
        $this->_dec = $dec;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $match = [];

        if (!preg_match('#^-?([1-9]\\d*|0)(\\.\\d+)?$#u', $value, $match)) {
            return $error($consts['INVALID'], []);
        }

        $match[2] = (isset($match[2])) ? $match[2] : '';
        if (strlen($match[1]) > $params['int'] && strlen($match[2]) > $params['dec'] + 1) {
            $error($consts['INVALID_INTDEC'], []);
        }
        elseif (strlen($match[1]) > $params['int']) {
            $error($consts['INVALID_INT'], []);
        }
        elseif (strlen($match[2]) > $params['dec'] + 1) {
            $error($consts['INVALID_DEC'], []);
        }
    }

    public function getMaxLength()
    {
        // マイナス記号＋整数部分＋小数点＋小数部分
        return 1 + $this->_int + 1 + $this->_dec;
    }

    public function getMin()
    {
        return '-' . $this->getMax();
    }

    public function getMax()
    {
        $n = str_repeat('9', $this->_int);
        if ($this->_dec > 0) {
            $n .= '.' . str_repeat('9', $this->_dec);
        }
        return $n;
    }

    public function getStep()
    {
        // 小数部が 0 なら整数なので step 1
        if ($this->_dec == 0) {
            return 1;
        }

        // 例えば 3 桁なら step 0.001
        return '0.' . str_repeat('0', $this->_dec - 1) . '1';
    }

    public function getType()
    {
        return 'number';
    }

    public function getFixture($value, $fields)
    {
        return $this->fixtureDecimal($this->_int, $this->_dec);
    }
}
