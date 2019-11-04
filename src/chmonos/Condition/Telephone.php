<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;

/**
 * 電話番号バリデータ
 *
 * あくまで電話番号「っぽい」検証に留める。
 *
 * - hyphen: bool
 *   - ハイフンを許容するか
 *   - null を渡すと「どちらでも良い」という挙動になる
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

    public function __construct($hyphen = null)
    {
        $this->_hyphen = $hyphen;

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

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        // 明らかに電話番号っぽくない場合のチェック
        if (mb_strlen($value) > $params['maxlength']) {
            return $error($consts['INVALID']);
        }

        // 電話番号っぽいが細部がおかしい場合
        if (!preg_match($params['pattern'], $value)) {
            if ($params['hyphen'] === null) {
                $error($consts['INVALID_TELEPHONE']);
            }
            else if ($params['hyphen'] === true) {
                $error($consts['INVALID_WITH_HYPHEN']);
            }
            else if ($params['hyphen'] === false) {
                $error($consts['INVALID_NONE_HYPHEN']);
            }
        }
    }

    public function getValidationParam()
    {
        return [
            'hyphen'    => $this->_hyphen,
            'pattern'   => $this->_getRegex(),
            'maxlength' => $this->getMaxLength(),
        ];
    }

    public function getMaxLength()
    {
        $length = 15;
        if ($this->_hyphen === false) {
            $length -= 2;
        }

        return $length;
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
}
