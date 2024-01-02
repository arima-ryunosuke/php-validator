<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 重複値バリデータ
 *
 * uri,emailaddress など delimiter 引数があるものに適用できる。
 * ただ大部分が↑のようなものであって、例えば textarea に改行区切りで何らかの値を入力する場合にも適用できる。
 *
 * - delimiter: string
 *   - 区切り文字。uri,emailaddress 等と一緒に使う場合はその delimiter と同じ値を渡す
 *   - ただし親和性が高いので null を渡せば自動設定されるようになっている（_setAutoDistinctDelimiter）
 */
class Distinct extends AbstractCondition
{
    public const INVALID     = 'DistinctInvalid';
    public const NO_DISTINCT = 'DistinctNoDistinct';

    protected static $messageTemplates = [
        self::INVALID     => 'Invalid value given',
        self::NO_DISTINCT => '重複した値が含まれています',
    ];

    protected $_delimiter;

    public function __construct($delimiter = null)
    {
        $this->_delimiter = $delimiter;

        parent::__construct();
    }

    public function getDelimiter()
    {
        return $this->_delimiter;
    }

    public function setDelimiter($delimiter)
    {
        $this->_delimiter = $delimiter;
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $value = preg_split($params['delimiter'], $value, -1, PREG_SPLIT_NO_EMPTY);

        if (count($value) !== count(array_unique($value))) {
            $error($consts['NO_DISTINCT']);
        }
    }
}
