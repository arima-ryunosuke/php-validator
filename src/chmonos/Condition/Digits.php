<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 整数バリデータ
 *
 * 整数だけでは味気ないので符号チェックと桁数チェックも兼ねている。
 *
 * - sign: ?string
 *   - "+","-" など許可する先頭の文字
 *   - 実質的には ltrim の引数に近い
 * - digit: ?int
 *   - 全体の桁数。0埋め必須な場合等に使う。 null だと桁数をチェックしない
 *   - この桁数制限は sign 分は含まない（Number+StringLength で代替できない最大の理由）
 * - mustDigit: bool
 *   - digit ピッタリを要求するか。 false にすると digit 未満も許容される
 */
class Digits extends AbstractCondition implements Interfaces\MaxLength
{
    public const INVALID       = 'notDigits';
    public const NOT_DIGITS    = 'digitsInvalid';
    public const INVALID_DIGIT = 'digitsInvalidDigit';

    protected static $messageTemplates = [
        self::INVALID       => 'Invalid value given',
        self::NOT_DIGITS    => '整数を入力してください',
        self::INVALID_DIGIT => '${_digit}桁で入力してください',
    ];

    protected $_sign;
    protected $_digit;
    protected $_mustDigit;

    public function __construct($sign = '+-', $digit = null, $mustDigit = true)
    {
        $this->_sign = $sign;
        $this->_digit = $digit;
        $this->_mustDigit = $mustDigit;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $value = ltrim($value, $params['sign']);

        if (!ctype_digit($value)) {
            $error($consts['NOT_DIGITS'], []);
            return;
        }
        if ($params['mustDigit'] && $params['digit'] !== null && $params['digit'] !== strlen($value)) {
            $error($consts['INVALID_DIGIT'], []);
            return;
        }
        if (!$params['mustDigit'] && $params['digit'] !== null && $params['digit'] < strlen($value)) {
            $error($consts['INVALID_DIGIT'], []);
            return;
        }
    }

    public function getMaxLength()
    {
        if ($this->_digit === null) {
            return null;
        }

        return $this->_digit + (strlen($this->_sign) ? 1 : 0);
    }

    public function getFixture($value, $fields)
    {
        return $this->fixtureArray(str_split($this->_sign)) . $this->fixtureString($this->_digit ?? rand(1, 4), '0123456789');
    }
}
