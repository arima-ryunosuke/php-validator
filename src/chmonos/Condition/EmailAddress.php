<?php
namespace ryunosuke\chmonos\Condition;

/**
 * メールアドレスバリデータ
 *
 * 前提としてかなり緩めの正規表現で検証するが、正規表現はパラメータで指定できる。
 *
 * - regex: string
 *   - 検証に使う正規表現
 * - delimiter: ?string
 *   - 非 null を渡すと複数値が許容され、指定文字がデリミタ（正規表現）として使用される
 *   - どのような文字を渡しても空白文字は取り除かれる（"," と ", " は実質同じ意味になる）
 */
class EmailAddress extends AbstractCondition implements Interfaces\MaxLength, Interfaces\MultipleValue
{
    public const INVALID        = 'emailAddressInvalid';
    public const INVALID_FORMAT = 'emailAddressInvalidFormat';

    protected static $messageTemplates = [
        self::INVALID        => 'Invalid value given',
        self::INVALID_FORMAT => 'メールアドレスを正しく入力してください',
    ];

    protected $_regex;
    protected $_delimiter;

    public function __construct($regex = null, $delimiter = null)
    {
        $this->_regex = $regex ?? '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/ui';
        $this->_delimiter = $delimiter;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if ($params['delimiter'] === null) {
            $value = $context['cast']('array', $value);
        }
        else {
            $value = preg_split($params['delimiter'], $value, -1, PREG_SPLIT_NO_EMPTY);
        }

        $context['foreach']($value, function ($key, $value, $params, $error, $consts) {
            if (!preg_match($params['regex'], $value)) {
                $error($consts['INVALID_FORMAT'], []);
                return false;
            }
        }, $params, $error, $consts);
    }

    public function getMaxLength()
    {
        if ($this->_delimiter !== null) {
            return null;
        }

        // メールアドレス最大長は256文字（RFC5321）
        return 256;
    }

    public function getType()
    {
        // @task InferableType を implement してないので有効になっていない
        // type=email のメールアドレスチェックは綿密すぎるのでとりあえず text
        return 'text';
    }

    public function getDelimiter()
    {
        return $this->_delimiter;
    }

    public function getFixture($value, $fields)
    {
        return "u" . $this->fixtureString(4) . "@example.jp";
    }
}
