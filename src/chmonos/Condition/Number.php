<?php
namespace ryunosuke\chmonos\Condition;

/**
 * Digits/Decimal/Range の複合条件
 *
 * - min: string|int|float
 *   - 数値の最小数
 * - max: string|int|null
 *   - 数値の最大数
 *
 * 整数であれば int でもいいが、小数の場合で有効桁を活かすなら float ではなく string で与えた方が良い。
 */
class Number extends AbstractCondition implements Interfaces\MaxLength, Interfaces\ImeMode, Interfaces\InferableType, Interfaces\Range
{
    public const INVALID        = 'NumberInvalid';
    public const INVALID_INT    = 'NumberInvalidInt';
    public const INVALID_DEC    = 'NumberInvalidDec';
    public const INVALID_INTDEC = 'NumberInvalidIntDec';
    public const INVALID_MIN    = 'NumberMin';
    public const INVALID_MAX    = 'NumberMax';
    public const INVALID_MINMAX = 'NumberMinMax';

    protected static $messageTemplates = [
        self::INVALID        => '数値を入力してください',
        self::INVALID_INT    => '整数部分を%int%桁以下で入力してください',
        self::INVALID_DEC    => '小数部分を%dec%桁以下で入力してください',
        self::INVALID_INTDEC => '整数部分を%int%桁、小数部分を%dec%桁以下で入力してください',
        self::INVALID_MIN    => '%min%以上で入力して下さい',
        self::INVALID_MAX    => '%max%以下で入力して下さい',
        self::INVALID_MINMAX => '%min%以上%max%以下で入力して下さい',
    ];

    protected $_int;
    protected $_dec;
    protected $_min;
    protected $_max;

    public function __construct(string $min, string $max)
    {
        [$minint, $mindec] = explode('.', (string) abs($min)) + [1 => ''];
        [$maxint, $maxdec] = explode('.', (string) abs($max)) + [1 => ''];

        $this->_int = (int) max(strlen($minint), strlen($maxint));
        $this->_dec = (int) max(strlen($mindec), strlen($maxdec));

        $this->_min = $min;
        $this->_max = $max;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $match = [];

        if (!preg_match('#^-?([1-9]\\d*|0)(\\.\\d+)?$#u', $value, $match)) {
            return $error($consts['INVALID']);
        }

        $match[2] = (isset($match[2])) ? $match[2] : '';
        if (strlen($match[1]) > $params['int'] && strlen($match[2]) > $params['dec'] + 1) {
            return $error($consts['INVALID_INTDEC']);
        }
        else if (strlen($match[1]) > $params['int']) {
            return $error($consts['INVALID_INT']);
        }
        else if (strlen($match[2]) > $params['dec'] + 1) {
            return $error($consts['INVALID_DEC']);
        }

        if (!(+$params['min'] <= +$value && +$value <= +$params['max'])) {
            return $error($consts['INVALID_MINMAX']);
        }
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }

    public function getMin()
    {
        return $this->_min;
    }

    public function getMax()
    {
        return $this->_max;
    }

    public function getStep()
    {
        // 小数部が 0 なら整数なので step 1
        if ($this->_dec === 0) {
            return '1';
        }

        // 例えば 3 桁なら step 0.001
        return '0.' . str_repeat('0', $this->_dec - 1) . '1';
    }

    public function getMaxLength()
    {
        // マイナス記号
        $minus = min($this->_min, $this->_max) < 0 ? 1 : 0;
        // 整数部分
        $int = $this->_int; // .123 のような表記は許容しない
        // 小数点＋小数部分
        $dec = $this->_dec === 0 ? 0 : 1 + $this->_dec;
        // マイナス記号＋整数部分＋小数点＋小数部分
        return $minus + $int + $dec;
    }

    public function getType()
    {
        return 'number';
    }
}
