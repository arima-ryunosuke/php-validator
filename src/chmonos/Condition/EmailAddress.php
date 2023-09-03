<?php
namespace ryunosuke\chmonos\Condition;

/**
 * メールアドレスバリデータ
 *
 * 前提としてかなり緩めの正規表現で検証するが、正規表現はパラメータで指定できる。
 *
 * - regex: string
 *   - 検証に使う正規表現
 */
class EmailAddress extends AbstractCondition implements Interfaces\MaxLength, Interfaces\ImeMode
{
    public const INVALID        = 'emailAddressInvalid';
    public const INVALID_FORMAT = 'emailAddressInvalidFormat';

    protected static $messageTemplates = [
        self::INVALID        => 'Invalid value given',
        self::INVALID_FORMAT => 'メールアドレスを正しく入力してください',
    ];

    protected $_regex;

    public function __construct($regex = null)
    {
        $this->_regex = $regex ?? '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/ui';

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if (!preg_match($params['regex'], $value)) {
            $error($consts['INVALID_FORMAT']);
        }
    }

    public function getMaxLength()
    {
        // メールアドレス最大長は256文字（RFC5321）
        return 256;
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }

    public function getType()
    {
        // @task InferableType を implement してないので有効になっていない
        // type=email のメールアドレスチェックは綿密すぎるのでとりあえず text
        return 'text';
    }
}
