<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 電話番号バリデータ
 *
 * あくまで電話番号「っぽい」検証に留める。
 *
 * - hyphen: bool
 *   - ハイフンを許容するか
 *   - null を渡すと「どちらでも良い」という挙動になる
 * - delimiter: ?string
 *   - 非 null を渡すと複数値が許容され、指定文字がデリミタ（正規表現）として使用される
 *   - どのような文字を渡しても空白文字は取り除かれる（"," と ", " は実質同じ意味になる）
 */
class Telephone extends AbstractCondition implements Interfaces\MaxLength, Interfaces\ImeMode, Interfaces\InferableType
{
    public const INVALID             = 'InvalidTelephone';
    public const INVALID_TELEPHONE   = 'InvalidTelephoneNumber';
    public const INVALID_WITH_HYPHEN = 'InvalidTelephoneWithHyphen';
    public const INVALID_NONE_HYPHEN = 'InvalidTelephoneNoneHyphen';

    protected static $messageTemplates = [
        self::INVALID             => '電話番号を正しく入力してください',
        self::INVALID_TELEPHONE   => '電話番号を入力してください',
        self::INVALID_WITH_HYPHEN => 'ハイフン付きで電話番号を入力してください',
        self::INVALID_NONE_HYPHEN => 'ハイフン無しで電話番号を入力してください',
    ];

    protected $_hyphen;
    protected $_delimiter;

    public function __construct($hyphen = null, $delimiter = null)
    {
        $this->_hyphen = $hyphen;
        $this->_delimiter = $delimiter;

        parent::__construct();
    }

    private function _getRegex()
    {
        if ($this->_hyphen === null) {
            return '/^\\d{1,5}-?\\d{1,4}-?\\d{2,4}$/';
        }
        if ($this->_hyphen === true) {
            return '/^\\d{1,5}-\\d{1,4}-\\d{2,4}$/';
        }
        if ($this->_hyphen === false) {
            return '/^\\d{1,5}\\d{1,4}\\d{2,4}$/';
        }

        throw new \UnexpectedValueException('hyphen is invalid value.');
    }

    private function _getMaxLength()
    {
        $length = 15;
        if ($this->_hyphen === false) {
            $length -= 2;
        }

        return $length;
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
            // 明らかに電話番号っぽくない場合のチェック
            if (mb_strlen($value) > $params['maxlength']) {
                $error($consts['INVALID']);
                return false;
            }

            // 電話番号っぽいが細部がおかしい場合
            if (!preg_match($params['pattern'], $value)) {
                if ($params['hyphen'] === null) {
                    $error($consts['INVALID_TELEPHONE']);
                    return false;
                }
                else if ($params['hyphen'] === true) {
                    $error($consts['INVALID_WITH_HYPHEN']);
                    return false;
                }
                else if ($params['hyphen'] === false) {
                    $error($consts['INVALID_NONE_HYPHEN']);
                    return false;
                }
            }
        }, $params, $error, $consts);
    }

    public function getValidationParam()
    {
        return [
            'hyphen'    => $this->_hyphen,
            'delimiter' => $this->_delimiter,
            'pattern'   => $this->_getRegex(),
            'maxlength' => $this->_getMaxLength(),
        ];
    }

    public function getMaxLength()
    {
        if ($this->_delimiter !== null) {
            return null;
        }

        return $this->_getMaxLength();
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }

    public function getType()
    {
        // type=tel はマーカー的なもので入力規則的なものはない（スマホなどのために指定しておいたほうが良い）
        return 'tel';
    }

    public function getFixture($value, $fields)
    {
        $hyphen = ($this->_hyphen === true || ($this->_hyphen !== false && $this->fixtureBool())) ? '-' : '';
        return implode($hyphen, [
            '0' . $this->fixtureInt(10, 99),
            '1' . $this->fixtureInt(100, 999),
            '2' . $this->fixtureInt(100, 999),
        ]);
    }
}
