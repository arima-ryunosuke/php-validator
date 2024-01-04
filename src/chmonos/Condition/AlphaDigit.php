<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 半角英数バリデータ
 *
 * 実質的には識別子バリデータ。
 *
 * - first_number: bool
 *   - 先頭数値を許可するか
 *   - 識別子というと先頭数値は許可されていないことが多い
 * - allow_underscore: bool
 *   - アンダースコアを許可するか
 *   - 識別子というとアルファベットの定義にアンダースコアが入ることが多い
 * - case: ?bool
 *   - 大文字小文字をどうするか
 *   - null:大文字小文字を区別しない, false:大文字のみ, true: 小文字のみ
 */
class AlphaDigit extends AbstractCondition implements Interfaces\ImeMode
{
    public const INVALID              = 'AlphaNumericInvalid';
    public const INVALID_FIRST_NUMBER = 'AlphaNumericFirstNumber';
    public const INVALID_UNDERSCORE   = 'AlphaNumericUnderscore';
    public const INVALID_UPPERCASE    = 'AlphaNumericUpperCase';
    public const INVALID_LOWERCASE    = 'AlphaNumericLowerCase';

    protected static $messageTemplates = [
        self::INVALID              => '半角英数字で入力してください',
        self::INVALID_FIRST_NUMBER => '先頭に数値は使えません',
        self::INVALID_UNDERSCORE   => 'アンダースコアは使えません',
        self::INVALID_UPPERCASE    => '大文字は使えません',
        self::INVALID_LOWERCASE    => '小文字は使えません',
    ];

    protected $_first_number;
    protected $_allow_underscore;
    protected $_case;

    public function __construct($first_number = false, $allow_underscore = true, $case = null)
    {
        $this->_first_number = $first_number;
        $this->_allow_underscore = $allow_underscore;
        $this->_case = $case;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if (!preg_match('/^[_a-z0-9]+$/i', $value)) {
            $error($consts['INVALID']);
            return;
        }

        if (!$params['first_number'] && ctype_digit(substr($value, 0, 1))) {
            $error($consts['INVALID_FIRST_NUMBER']);
        }
        if (!$params['allow_underscore'] && strpos($value, '_') !== false) {
            $error($consts['INVALID_UNDERSCORE']);
        }
        if ($params['case'] === false && strtoupper($value) !== $value) {
            $error($consts['INVALID_LOWERCASE']);
        }
        if ($params['case'] === true && strtolower($value) !== $value) {
            $error($consts['INVALID_UPPERCASE']);
        }
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }

    public function getFixture($value, $fields)
    {
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $alphabet = '';
        if ($this->_case === null) {
            $alphabet .= $lower . $upper;
        }
        elseif ($this->_case === false) {
            $alphabet .= $upper;
        }
        elseif ($this->_case === true) {
            $alphabet .= $lower;
        }
        if ($this->_allow_underscore) {
            $alphabet .= '_';
        }

        if ($this->_first_number) {
            $value = $this->fixtureInt(0, 9);
        }
        else {
            $value = $this->fixtureString(1, $alphabet);
        }
        $value .= $this->fixtureString(rand(10, 32), $alphabet . '0123456789');
        return $value;
    }
}