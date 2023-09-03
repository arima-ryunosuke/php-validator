<?php
namespace ryunosuke\chmonos\Condition;

/**
 * パスワードバリデータ
 *
 * - charlists: string|array
 *   - 文字種を指定する（後述）
 * - repeat: int
 *   - 指定文字種を何文字以上含めなければならないか
 *
 * charlists のキーは表示文字列として使用される。例えば
 * [
 *   '半角英字' => 'abcdefghijklmnopqrstuvwxyz',
 *   '半角数字' => '0123456789',
 * ]
 * とすれば「半角英字, 半角数字を含めてください」となる。
 *
 * プリセットとして
 * - alpha (大文字小文字半角英数)
 * - lower (小文字半角英数)
 * - upper (大文字半角英数)
 * - numeric (半角数字)
 * - symbol (よく使われる記号系)
 * が用意されている。
 * 上記を含むような文字列を指定すれば使用される（'alpha_numeric' で半角英数字になる）。
 */
class Password extends AbstractCondition implements Interfaces\ImeMode, Interfaces\InferableType
{
    public const INVALID               = 'InvalidPassword';
    public const INVALID_PASSWORD_LESS = 'InvalidPasswordLess';
    public const INVALID_PASSWORD_WEAK = 'InvalidPasswordWeak';

    protected static $messageTemplates = [
        self::INVALID               => 'Invalid value given',
        self::INVALID_PASSWORD_LESS => '%char_types%を含めてください',
        self::INVALID_PASSWORD_WEAK => '%char_types%のいずれかを%repeat%文字以上含めてください',
    ];

    private static $preset = [
        'alpha'   => [
            'a～z' => 'abcdefghijklmnopqrstuvwxyz',
            'A～Z' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        ],
        'lower'   => ['a～z' => 'abcdefghijklmnopqrstuvwxyz'],
        'upper'   => ['A～Z' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'],
        'numeric' => ['0～9' => '0123456789'],
        'symbol'  => ['!#$%+_' => '!#$%+_'],
    ];

    protected $_charlists;
    protected $_char_types;
    protected $_regexes;
    protected $_repeat;

    public function __construct($charlists = 'alpha_numeric_symbol', $repeat = 2)
    {
        // 文字列だったら preset に一致するものを使う
        if (is_string($charlists)) {
            $backup = $charlists;
            $charlists = [];
            foreach (self::$preset as $key => $list) {
                if (strpos($backup, $key) !== false) {
                    $charlists += $list;
                }
            }
        }

        $this->_charlists = $charlists;
        $this->_char_types = implode(', ', array_keys($charlists));
        $this->_regexes = array_map(function ($v) { return '/[' . preg_quote($v, '/') . ']/'; }, $charlists);
        $this->_repeat = $repeat;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $fulfill = $context['foreach']($params['regexes'], function ($key, $regex, $value, $error, $consts) {
            if (!preg_match($regex, $value)) {
                $error($consts['INVALID_PASSWORD_LESS']);
                return false;
            }
        }, $value, $error, $consts);

        if (!$fulfill) {
            return;
        }

        $counts = array_count_values(str_split($value, 1));
        if (count($counts) < count($params['regexes']) * $params['repeat']) {
            $error($consts['INVALID_PASSWORD_WEAK']);
        }
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }

    public function getType()
    {
        return 'password';
    }
}
