<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 正規表現バリデータ（全行）
 *
 * - pattern: string
 *   - 正規表現文字列
 * - delimiter: string
 *   - 行区切りデリミタ
 * - errorLimit: ?int
 *   - エラー数。 例えば1にするとエラーは1つで打ち切られる。null で無制限
 */
class RegexAll extends AbstractCondition implements Interfaces\InferableType, Interfaces\MultipleValue
{
    public const INVALID     = 'regexAllInvalid';
    public const ERROROUS    = 'regexAllErrorous';
    public const NOT_MATCH   = 'regexAllNotMatch';
    public const ERROR_LIMIT = 'regexAllErrorLimit';

    protected static $messageTemplates = [
        self::INVALID     => 'Invalid value given',
        self::ERROROUS    => 'There was${_pattern}',
        self::NOT_MATCH   => '${line}行目(${text})がパターンに一致しません',
        self::ERROR_LIMIT => 'エラーが多すぎるためすべては表示しません',
    ];

    protected $_pattern;
    protected $_delimiter;
    protected $_errorLimit;

    public function __construct(
        string $pattern,
        string $delimiter = "#\n#",
        ?int $errorLimit = null,
    ) {
        $this->_pattern = $pattern;
        $this->_delimiter = $delimiter;
        $this->_errorLimit = $errorLimit;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            return $error($consts['INVALID'], []);
        }

        $count = $context['cast']('object', 0);
        $lines = preg_split($params['delimiter'], $value);
        $context['foreach']($lines, function ($key, $value, $params, $error, $consts, $count) {
            if (!preg_match($params['pattern'], $value)) {
                if ($params['errorLimit'] !== null && $params['errorLimit'] < ++$count['scalar']) {
                    $error($consts['ERROR_LIMIT'], []);
                    return false;
                }
                $error($consts['NOT_MATCH'], [
                    ['line', +$key + 1],
                    ['text', $value],
                ]);
            }
        }, $params, $error, $consts, $count);
    }

    public function getType()
    {
        return 'textarea';
    }

    public function getDelimiter()
    {
        return $this->_delimiter;
    }

    public function getFixture($value, $fields)
    {
        // 「ある正規表現にマッチする適当な文字列」を生成するのは多くの場合困難で、用途が決まっているなら専用の Consition を設けるべき
        // ここでは assert や notice で通知することを想定して親実装をそのままではなく明示的に記述している
        // e.g. assert("#[0-9]+#", Digits で十分)
        // e.g. assert("#[a-z-]+\.[a-z-0-9]#u", Hostname で十分)
        return $value;
    }
}
